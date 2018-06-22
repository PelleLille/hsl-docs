.. module:: core

Standard library
================

Functions which are documented in this chapter are considered `core` functions hence are available in all `contexts`. Functions in the standard library may be recognized by the fact that they are all in lowercase.

* **Array** :func:`array_keys` :func:`array_filter` :func:`array_map` :func:`array_reduce` :func:`array_reverse` :func:`array_sort` :func:`count` :func:`explode` :func:`implode` :func:`in_array` :func:`range`
* **Cryptographic** :func:`hmac_md5` :func:`hmac_sha1` :func:`hmac_sha2` :func:`md5` :func:`sha1` :func:`sha2` :func:`hash`
* **Data types** :func:`array` :func:`number` :func:`string` :func:`is_array` :func:`is_function` :func:`is_number` :func:`is_object` :func:`is_string` :func:`isset` :func:`unset`
* **Date and time** :func:`executiontime` :func:`rand` :func:`sleep` :func:`strftime` :func:`strptime` :func:`time` :func:`timelocal` :func:`uptime`
* **DNS** :func:`dns` :func:`dns4` :func:`dns6` :func:`dnscname` :func:`dnsmx` :func:`dnsns` :func:`dnsptr` :func:`dnstxt` :func:`is_subdomain`
* **Encodings and JSON** :func:`base64_encode` :func:`base64_decode` :func:`csv_explode` :func:`json_encode` :func:`json_decode` :func:`pack` :func:`unpack`
* **File and HTTP** :func:`file` :func:`file_get_contents` :func:`in_file` :func:`http` :class:`File`
* **Mail** :func:`dnsbl` :func:`spf` :func:`globalview`
* **Mathematical** :func:`abs` :func:`ceil` :func:`floor` :func:`log` :func:`pow` :func:`round` :func:`sqrt`
* **MIME** :class:`MIME`
* **Misc** :func:`serial` :func:`gethostname` :func:`uuid` :func:`syslog` :func:`stat` :func:`in_network` :func:`inet_ntop` :func:`inet_pton` :func:`rate` :func:`mail`
* **Protocols** :func:`smtp_lookup_rcpt` :func:`smtp_lookup_auth` :func:`ldap_search` :func:`ldap_bind` :func:`radius_authen` :func:`tacplus_authen` :func:`tacplus_author`
* **String** :func:`chr` :func:`ord` :func:`str_repeat` :func:`str_replace` :func:`strlen` :func:`strpos` :func:`strrpos` :func:`strtolower` :func:`strtoupper` :func:`substr` :func:`trim` :func:`pcre_match` :func:`pcre_match_all` :func:`pcre_quote` :func:`pcre_replace`
* **Socket** :class:`Socket` :class:`TLSSocket`

Array
-----

.. function:: array_keys(array)

  Returns the keys in the array.

  :param array array: the array
  :return: array's keys
  :rtype: array

.. function:: array_filter(callback, array)

  Returns the filtered items from the array using a callback.

  :param function callback: the callback
  :param array array: the array
  :return: array of filtered values, keys are preserved
  :rtype: array

  The function should take one argument (value) and return a boolean value.

.. code-block:: hsl

	array_filter(function ($x) { return $x % 2 == 0; }, [0, 1, 2, 3]); // even values
	array_filter(is_number, [0, "Hello World", 2]);

.. function:: array_map(callback, array)

  Returns values from the array with the callback applied.

  :param function callback: the callback
  :param array array: the array
  :return: array of values, keys are preserved
  :rtype: array

  The function should take one argument (value) and return a value.

.. code-block:: hsl

	array_map(function ($x) { return $x * 2; }, [0, 1, 2, 3]); // double values

.. function:: array_reduce(callback, array, [initial])

  Reduces the values in the array using the callback from left-to-right, optionally starting with a initial value.

  :param function callback: the callback
  :param array array: the array
  :param any initial: the initial value
  :return: a single value
  :rtype: any

  The function should take two arguments (carry and value) and return a value.

  If no initial value is provided and;

	* the array is empty, an error will be raised.
	* the array contains one value, that value will be returned.

.. code-block:: hsl

	array_reduce(function ($carry, $x) { return $carry + $x; }, [0, 1, 2, 3]); // sum values

.. function:: array_reverse(array)

  Return array in reverse order

  :param array array: the array
  :return: array in reverse order
  :rtype: array

.. function:: array_sort(callback, array, [options])

  Returns the array sorted (with index association maintained) using the callback function to determine the order. The sort is not guaranteed to be stable.

  :param function callback: the callback
  :param array array: the array
  :param array options: options array
  :return: a sorted array
  :rtype: array

  The following options are available in the options array.

   * **keys** (boolean) Sort the array based on their keys. The default is ``false``.

  The callback function should take two arguments (a and b) and return true if a is less-than b.

.. code-block:: hsl

	array_sort(function ($a, $b) { return $a < $b; }, [2, 3, 1]); // sort
	array_sort(function ($a, $b) { return $a > $b; }, [2, 3, 1]); // reverse-sort

.. note::

  Some other languages (eg. javascript and PHP) use a trivalue function (-1, 0, 1) in a similar way in order to determine the order. HSL does not since if needed, a trivalue function may be simulated internally using the provided less-than function. Further some sorting implementation may only need the less-than result hence the greater-than and equality result may be superfluous to establish.

	.. code-block:: hsl

		function trivalue($a, $b, $lessthan)
		{
			if ($lessthan($a, $b)) return -1;
			if ($lessthan($b, $a)) return 1;
			return 0;
		}

.. function:: count(array)

  Counts items in an array.

  :param array array: the array
  :return: the number of items in array
  :rtype: number

.. function:: explode(delimiter, string, [limit = 0])

  Splits the string into an array on the delimiter.

  :param string delimiter: the delimiter
  :param string string: the string
  :param number limit: the maximum number of parts returned
  :return: an array of strings
  :rtype: array

  .. code-block:: hsl

	explode(" ", "how are you",  2) // ["how","are you"]
	explode(" ", "how are you", -2) // ["how are","you"]

.. function:: implode(glue, array)

  Joins the array with the glue.

  :param string glue: the glue
  :param array array: the array
  :return: a string from an array
  :rtype: string

.. function:: in_array(needle, array)

  Returns true if needle is found in the array.

  :param any needle: the value to match or a callback function
  :param array array: the array
  :return: true if needle is found
  :rtype: boolean

  The needle function should take one argument (the current item) and return a boolean value.

.. function:: range(start, stop, [step = 1])

  Returns an array from a numeric range (inclusive) with the given steps.

  :param number start: the first number
  :param number stop: the last number (that will occur)
  :param number step: the step between numbers
  :return: an array with numbers
  :rtype: array

  .. code-block:: hsl

	  foreach (range(0, 9) as $i) // 0,1,2,..,9
		echo $i;

Cryptographic
-------------

.. function:: hmac_md5(key, s)

  Return the HMAC MD5 hash of s with the key.

  :param string key: the HMAC key
  :param string s: the value to hash
  :return: the hash value hex encoded
  :rtype: string

.. function:: hmac_sha1(key, s)

  Return the HMAC SHA1 hash of s with the key.

  :param string key: the HMAC key
  :param string s: the value to hash
  :return: the hash value hex encoded
  :rtype: string

.. function:: hmac_sha2(key, s, hashsize)

  Return the HMAC SHA2 hash of s with the key.

  :param string key: the HMAC key
  :param string s: the value to hash
  :param number hashsize: the hash size (must be 256 or 512)
  :return: the hash value hex encoded
  :rtype: string

.. function:: md5(s)

  Return the MD5 hash of s.

  :param string s: the value to hash
  :return: the hash value hex encoded
  :rtype: string

.. function:: sha1(s)

  Return the SHA1 hash of s.

  :param string s: the value to hash
  :return: the hash value hex encoded
  :rtype: string

.. function:: sha2(s, hashsize)

  Return the SHA2 hash of s.

  :param string s: the value to hash
  :param number hashsize: the hash size (must be 256 or 512)
  :return: the hash value hex encoded
  :rtype: string

.. function:: hash(string)

  Return the numeric hash value of the input string. The hash value is same for equal strings.

  :param string string: string to be hashed
  :return: a hash value
  :rtype: number

Data types
----------

.. function:: array([...args])

  This function creates an array.

  :param any ....args: the input
  :return: an array
  :rtype: array

  .. note::

	`array` is not a function, it's a language construct to create an :ref:`array <arraytype>` type. It's an alias for the short array syntax ``[]``.

.. function:: number(x)

  This function converts the input of x to the number type. Decimal and hexadecimal (`Ox`) numbers are supported.

  :param any x: the input
  :return: a number
  :rtype: number

.. function:: string(x)

  This function converts the input of s to the string type, hence converting it to its string representation.

  :param any x: the input
  :return: a string
  :rtype: string

.. function:: is_array(a)

  Returns true if the type of a is an array.

  :param any a: the input
  :return: the result
  :rtype: boolean

.. function:: is_function(f)

  Returns true if the type of f is a function.

  :param any f: the input
  :return: the result
  :rtype: boolean

.. function:: is_number(n)

  Returns true if the type of n is a number.

  :param any n: the input
  :return: the result
  :rtype: boolean

.. function:: is_object(o)

  Returns true if the type of o is an object.

  :param any o: the input
  :return: the result
  :rtype: boolean

.. function:: is_string(s)

  Returns true if the type of s is a string.

  :param any s: the input
  :return: the result
  :rtype: boolean

.. function:: isset(x)

  Returns true if the variable is defined.

	.. note::

		This is not a regular function. It's a language construct and will only accept variables as input.

  :param variable x: a variable
  :return: the result
  :rtype: boolean

.. function:: unset(x)

  Unsets the variable or array index or slice of x, it return true if the variable or array index was defined.

	.. note::

		This is not a regular function. It's a language construct and will only accept variables as input.

  :param variable x: a variable
  :return: if x was unset
  :rtype: boolean


Date and time
-------------

.. function:: executiontime()

  Return the elapsed time since the beginning of the code execution.

  :return: the time in seconds (with decimals)
  :rtype: number

.. function:: rand(x, y)

  Return a random integer between x and y (inclusive).

  :param number x: first possible number
  :param number y: last possible number
  :return: the random number
  :rtype: number

.. function:: sleep(x)

  Pause the code execution for x seconds.

  :param number x: the number of seconds to sleep
  :return: the time slept in seconds (with decimals)
  :rtype: number

.. function:: strftime(format, [time])

  Format according to the `strftime <http://www.freebsd.org/cgi/man.cgi?query=strftime>`_ manual.

  .. code-block:: hsl

	 echo strftime("%H:%M:%S"); // prints current time eg "13:58:38"

  :param string format: the format string
  :param number time: the default is current time without timezone
  :return: the time formatted (max length 100)
  :rtype: string

.. function:: strptime(datestring, format)

  Parse a date string according to the `strftime <http://www.freebsd.org/cgi/man.cgi?query=strftime>`_ manual with the time without timezone.

  .. code-block:: hsl

	 echo strptime("13:58:38", "%H:%M:%S"); // prints time of today at "13:58:38"

  :param string datestring: the date string
  :param string format: the format string
  :return: the time in seconds
  :rtype: number

.. function:: time()

  Return elapsed seconds (unix time) since 1970-01-01T00:00:00Z without timezone.

  :return: the time in seconds (with decimals)
  :rtype: number

.. function:: timelocal()

  Return elapsed seconds (unix time) since 1970-01-01T00:00:00Z with timezone.

  :return: the time in seconds (with decimals)
  :rtype: number

.. function:: uptime()

  Return the monotonic time since system boot. Monotonic time is by definition suitable for relative time keeping, in contrast to :func:`time`. If you want to obtain the script execution time use :func:`executiontime`.

  :return: the time in seconds (with decimals)
  :rtype: number

DNS
---

.. function:: dns(host, [options])

  Query for the A and AAAA record of a hostname.

  .. include:: func_dns.rst

  .. code-block:: hsl

	echo dns("nxdomain.halon.se");
	// []
	echo dns("nxdomain.halon.se", ["extended_result" => true]);
	// ["error"=>"NXDOMAIN","dnssec"=>0]

	echo dns("halon.se");
	// [0=>"54.152.237.238"]
	echo dns("halon.se", ["extended_result" => true]);
	// ["result"=>[0=>"54.152.237.238"],"dnssec"=>0]

.. function:: dns4(host, [options])

  Query the resolvers for the A record of the hostname.

  .. include:: func_dns.rst

.. function:: dns6(host, [options])

  Query the resolvers for the AAAA record of the hostname.

  .. include:: func_dns.rst

.. function:: dnscname(host, [options])

  Query the resolvers for the CNAME record of the hostname.

  .. include:: func_dns.rst

.. function:: dnsmx(host, [options])

  Query the resolvers for the MX record of the hostname.

  .. include:: func_dns.rst

.. function:: dnsns(host, [options])

  Query the resolvers for the NS record of the hostname.

  .. include:: func_dns.rst

.. function:: dnsptr(host, [options])

  Query the resolvers for the PTR record of the address.

  .. include:: func_dns.rst

.. function:: dnstxt(host, [options])

  Query the resolvers for the TXT record of the hostname.

  .. include:: func_dns.rst

.. function:: is_subdomain(d, domain)

  Test if d is subdomain of domain. If the domain starts with a dot ``.`` it must be a subdomain of domain, hence it will **not** even if `d == domain`.

  .. code-block:: hsl

	is_subdomain("www.halon.io", "halon.io"); // true
	is_subdomain("halon.io", "halon.io"); // true
	is_subdomain("www.halon.io", ".halon.io"); // true
	is_subdomain("halon.io", ".halon.io"); // false

  :param string d: the subdomain
  :param string domain: the domain
  :return: if d is a subdomain of domain
  :rtype: boolean

Encodings and JSON
------------------

.. function:: base64_encode(string)

  Base64 encode the string.

  :param string string: the input string
  :return: the base64 representation
  :rtype: string

.. function:: base64_decode(string)

  Base64 decode the string.

  :param string string: the input string
  :return: the string representation
  :rtype: string

.. function:: csv_explode(string)

  CSV explode the string.

  :param string string: CSV formated string
  :return: an array of strings
  :rtype: array

.. function:: json_encode(value, [options])

  JSON encode a HSL data type.

  :param any value: HSL data type
  :param array options: options array
  :return: a JSON representation of value
  :rtype: string

  The following options are available in the options array.

   * **ensure_ascii** (boolean) Convert all non-ASCII characters (UTF-8) to unicode (`\\uXXXX`). The default is ``true``.
   * **pretty_print** (boolean) Pretty print the JSON output. The default is ``false``.

  Encode an array, number or string into a JSON representation (string). The encoding distinguishes arrays from objects if they are sequentially numbered from zero. On encoding errors an object with the data type of undefined is returned. All non-ASCII characters will be escaped as Unicode code points (\\uXXXX).

  .. note::

	  Since object keys are converted to strings (even numeric once) a :func:`json_encode` followed by a :func:`json_decode` does not always yield the same result.

.. function:: json_decode(string)

  Decodes a JSON string into a HSL data type.

  :param string string: JSON serialized data
  :return: the decoded string as the correct type, and on errors ``None`` is returned
  :rtype: any

  The following translations are done (JSON to HSL).

  * **object** to **associative array** (is_array)
  * **array** to **array** (is_array)
  * **string** to **string** (is_string)
  * **number** to **number** (is_number)
  * **true** to ``1`` (is_number)
  * **false** to ``0`` (is_number)
  * **null** to **none** (check for expected type instead)

.. function:: pack(format, [...args])

  Pack arguments into a binary string. On error ``None`` is returned.

  :param string format: the pack format
  :param any ....args: the arguments for the pack format
  :return: the packed data
  :rtype: string

  The format may contain the following types. Some types may be followed by a `*` (an end-of-argument(s) repeater or a numeric repeater, eg. `"Z*C3"`).

  +-------+------------+-------------------------------+----------+-------+
  | Code  | Repeaters  | Type                          | HSL type | Bytes |
  +=======+============+===============================+==========+=======+
  | ``a`` | *n*, ``*`` | String                        | String   | 1     |
  +-------+------------+-------------------------------+----------+-------+
  | ``C`` | *n*, ``*`` | Char                          | Number   | 1     |
  +-------+------------+-------------------------------+----------+-------+
  | ``e`` | *n*, ``*`` | Double (LE)                   | Number   | 8     |
  +-------+------------+-------------------------------+----------+-------+
  | ``E`` | *n*, ``*`` | Double (BE)                   | Number   | 8     |
  +-------+------------+-------------------------------+----------+-------+
  | ``H`` | *n*, ``*`` | Hex                           | String   | 1     |
  +-------+------------+-------------------------------+----------+-------+
  | ``n`` | *n*, ``*`` | Unsigned short (16 bit, BE)   | Number   | 2     |
  +-------+------------+-------------------------------+----------+-------+
  | ``N`` | *n*, ``*`` | Unsigned long (32 bit, BE)    | Number   | 4     |
  +-------+------------+-------------------------------+----------+-------+
  | ``v`` | *n*, ``*`` | Unsigned short (16 bit, LE)   | Number   | 2     |
  +-------+------------+-------------------------------+----------+-------+
  | ``V`` | *n*, ``*`` | Unsigned long (32 bit, LE)    | Number   | 4     |
  +-------+------------+-------------------------------+----------+-------+
  | ``x`` | *n*        | NULL                          |          | 1     |
  +-------+------------+-------------------------------+----------+-------+
  | ``Z`` | *n*, ``*`` | String (NULL terminated)      | String   | 1     |
  +-------+------------+-------------------------------+----------+-------+

.. function:: unpack(format, data, [offset = 0])

  Unpack data from a binary string. On error ``None`` is returned.

  :param string format: the unpack format
  :param string data: the packed data
  :param number offset: the offset to begin unpack from
  :return: the unpacked data
  :rtype: array

  The format may contain the following types. Some types may be followed by a `*` (an end-of-argument(s) repeater or a numeric repeater, eg. `"Z*C3"`).

  +-------+------------+-------------------------------+----------+-------+
  | Code  | Repeaters  | Type                          | HSL type | Bytes |
  +=======+============+===============================+==========+=======+
  | ``a`` | *n*, ``*`` | String                        | String   | 1     |
  +-------+------------+-------------------------------+----------+-------+
  | ``c`` | *n*, ``*`` | Signed char                   | Number   | 1     |
  +-------+------------+-------------------------------+----------+-------+
  | ``C`` | *n*, ``*`` | Char                          | Number   | 1     |
  +-------+------------+-------------------------------+----------+-------+
  | ``e`` | *n*, ``*`` | Double (LE)                   | Number   | 8     |
  +-------+------------+-------------------------------+----------+-------+
  | ``E`` | *n*, ``*`` | Double (BE)                   | Number   | 8     |
  +-------+------------+-------------------------------+----------+-------+
  | ``H`` | *n*, ``*`` | Hex                           | String   | 1     |
  +-------+------------+-------------------------------+----------+-------+
  | ``n`` | *n*, ``*`` | Unsigned short (16 bit, BE)   | Number   | 2     |
  +-------+------------+-------------------------------+----------+-------+
  | ``N`` | *n*, ``*`` | Unsigned long (32 bit, BE)    | Number   | 4     |
  +-------+------------+-------------------------------+----------+-------+
  | ``v`` | *n*, ``*`` | Unsigned short (16 bit, LE)   | Number   | 2     |
  +-------+------------+-------------------------------+----------+-------+
  | ``V`` | *n*, ``*`` | Unsigned long (32 bit, LE)    | Number   | 4     |
  +-------+------------+-------------------------------+----------+-------+
  | ``x`` | *n*        | Skip bytes                    |          | 1     |
  +-------+------------+-------------------------------+----------+-------+
  | ``Z`` | *n*, ``*`` | String (excluding NULL)       | String   | 1     |
  +-------+------------+-------------------------------+----------+-------+


File and HTTP
-------------
The filename may point to a file in the configuration ``file:X`` or a file relative on the accessible filesystem ``file://filename.txt``. If the URI scheme is missing, the default is to use ``file:``.

.. function:: file(filename)

  Return the content of the filename as an array line by line (without CR/LF).

  :param string filename: the file name
  :return: the file content as an array
  :rtype: array

.. function:: file_get_contents(filename)

  Return the content of the filename as a string.

  :param string filename: the file name
  :return: the file content as a string
  :rtype: string

.. function:: in_file(needle, filename, [options])

  Searches for a needle at the beginning (or at `index`) of each line in filename. If found, the line is returned as an array separated by the `delimiter`.

  :param any needle: the string to match or a callback function
  :param string filename: the file name
  :param array options: options array
  :return: if word is found in string, return all words on that line as an array
  :rtype: array

  The following options are available in the options array.

   * **type** (string) may be ``text/plain`` or ``text/csv``. In `text/csv` mode the delimiter is changed to ``,`` and the first line may be used as ``index``. The default type is ``text/plain``.
   * **delimiter** (string) separates words. The default is a white space for `text/plain` and ``,`` for `text/csv`.
   * **assoc** (boolean) in `text/csv` mode the first line may be used as associative index for the returned array. The default is ``true``.
   * **index** (number) the word index to search for (indexed at zero). The default is ``0`` (the first word).

  The needle function should take one argument (the line, as an array of words) and return a boolean value.

  .. note::

	Example using a CSV file; below is the content of ``file:1``::

		ip,comment
		192.168.1.25,webserver
		192.168.1.26,mailserver

	.. code-block:: hsl

		$infile = in_file($senderip, "file:1", ["type" => "text/csv"]);
		if ($infile) {
			// e.g. ["ip" => "192.168.1.26", "comment" => "mailserver"]
		}
		$infile = in_file(function ($v) {
						global $senderip;
						return $v["ip"] == $senderip;
					}, "file:1", ["type" => "text/csv"]);

.. function:: http(url, [options, [get, [post]]])

  Make HTTP/HTTPS request to a URL and return the content.

  :param string url: URL to request
  :param array options: options array
  :param array get: GET variables, replaced and encoded in URL as $1, $2...
  :param post: POST data as an array or a string for raw POST data
  :type post: array or string
  :return: if the request was successful (2XX) the content is returned, otherwise the type ``None`` is returned
  :rtype: string or array

  The following options are available in the options array.

   * **extended_result** (boolean) Get a more extended result. The default is ``false``.
   * **connect_timeout** (number) Connection timeout (in seconds). The default is ``10`` seconds.
   * **timeout** (number) Timeout (in seconds) waiting for data once the connection is established. The default is to wait indefinitely.
   * **max_file_size** (number) Maximum file size (in bytes). The default is no limit.
   * **sourceip** (string) Explicitly bind a ``netaddr:X`` or an IP address. The default is to be chosen by the system.
   * **method** (string) Request method. The default is ``GET`` unless ``POST`` data is sent.
   * **headers** (array) An array of additional HTTP headers.
   * **response_headers** (boolean) Return the full request, including response headers (regardless of HTTP status). The default is ``false``.
   * **tls_verify_peer** (boolean) Verify peer certificate. The default is ``true``.
   * **tls_verify_host** (boolean) Verify certificate hostname (CN). The default is ``false``.
   * **tls_default_ca** (boolean) Load additional TLS certificates (ca_root_nss). The default is ``false``.
   * **background** (boolean) Perform request in the background. In which case this function returns ``true`` if the queueing was successful, otherwise ``None`` on errors. The default is ``false``.
   * **background_hash** (number) Assign this request to a specific queue. If this value is higher than the number of queues, it's chosen by modulus. The default is queue ``0``.
   * **background_retry_count** (number) Number of retry attempts made after the initial failure. The default is ``0``.
   * **background_retry_delay** (number) The delay, in seconds, before each retry attempt. The default is ``0`` seconds.

  If the option ``extended_result`` result is ``true``. This function will return an array containing the ``status`` code and ``content``. If no valid HTTP response is receivied `None` is return.

	.. code-block:: hsl

	  $response = http("http://halon.io/", ["extended_result" => true]);
	  if ($response) {
		  echo $response;
	  }

.. class:: File()

  A File class cannot be created at the moment, only retured by :func:`~data.GetMailFile`. This resource is automatically garbage collected (closed) once the object is destroyed.

  .. code-block:: hsl

	$file = GetMailFile();
	while ($data = $file->read(8192))
		echo $data;

  .. function:: File.close()

	  Close the file and destroy the internal file resource.

	  :return: none
	  :rtype: None

	  .. note::

		Files are automatically garbage collected (closed). However you may want to explicitly call close.

  .. function:: File.read(len)

	  Read data from file. On EOF an empty string is returned. On error ``None`` is returned.

	  :param number len: bytes to read
	  :return: data
	  :rtype: string or None

  .. function:: File.seek(offset, [whence = "SEEK_SET"])

	  Seek to the offset in the file. On error ``None`` is returned.

	  :param number offset: the offset
	  :param string whence: the position specified by whence
	  :return: position
	  :rtype: number or None

	  Whence may be any of

	  +----------+------------------------------------------+
	  | Name     | Position                                 |
	  +==========+==========================================+
	  | SEEK_CUR | relative offset to the current position  |
	  +----------+------------------------------------------+
	  | SEEK_SET | absolute offset from the beginning       |
	  +----------+------------------------------------------+
	  | SEEK_END | negative offset from the end of the file |
	  +----------+------------------------------------------+

  .. function:: File.tell()

	  Get the current file position. On error ``None`` is returned.

	  :return: position
	  :rtype: number or None

Mail
----

.. function:: dnsbl(ip, hostname, [resolvers, [timeout = 5]])

  Query the resolvers for the DNSBL status of an address. If no resolvers are given, the system default is used.

  :param string ip: IP or IPv6 address to check
  :param string hostname: in DNSBL list
  :param array resolvers: list of resolvers
  :param number timeout: timeout in seconds
  :return: list of IP addresses
  :rtype: array

  This function works by reversing the IP addresses octets and appending to the hostname parameter.

.. function:: spf(ip, helo, domain, [options])

  Check the SPF status of the senderdomain.

  :param string ip: IP or IPv6 address to check
  :param string helo: HELO/EHLO host name
  :param string domain: domain too lookup
  :param array options: options array
  :return: ``0`` if the addresses passed, ``20`` for softfail, ``50`` if the status is unknown and ``100`` if the spf failed.
  :rtype: number

  The following options are available in the options array.

   * **extended_result** (boolean) If ``true`` an associative array with ``result`` is returned with the string result as defined by libspf2 (eg. ``pass``). The default is ``false``.

.. function:: globalview(ip)

  Check the Cyren Globalview reputation for an IP.

  :param string ip: IP or IPv6 address to check
  :return: the recommended action to take for the ip ``accept``, ``tempfail`` or ``permfail``.
  :rtype: string

Mathematical
------------

.. function:: abs(x)

  Return the absolute value of a number.

  :param number x: the numeric value to process
  :return: the absolute value of x
  :rtype: number

.. function:: ceil(x)

  Return the integer value of a number by rounding up if necessary.

  :param number x: the numeric value to process
  :return: the integer value of x
  :rtype: number

.. function:: floor(x)

  Return the integer value of a number by rounding down if necessary.

  :param number x: the numeric value to process
  :return: the integer value of x
  :rtype: number

.. function:: log(x, [y = e])

  Return the logarithm of base x and exponent y.

  :param number x: the numeric value to process
  :param number y: the base
  :return: the logarithm value of x to base y
  :rtype: number

.. function:: pow(x, y)

  Return base x raised to the power of the exponent y.

  :param number x: the numeric value to process
  :param number y: the exponent
  :return: the x to power of y
  :rtype: number

.. seealso::
	It's significantly faster to use the ** operator since it's an operator and not a function.

.. function:: round(x, [y = 0])

  Return x rounded to precision of y decimals.

  :param number x: the numeric value to process
  :param number y: the number of decimals
  :return: the value x rounded to y
  :rtype: number

.. function:: sqrt(x)

  Return the square root of x.

  :param number x: the numeric value to process
  :return: the square root of x
  :rtype: number

MIME
----

.. class:: MIME()

  The MIME object "constructor" takes no function arguments, and returns a new MIME object.

  The standard library's MIME object is a "string builder" to construct MIME parts. In the :doc:`DATA <data>` context there is an similar :class:`~data.MIME` object as well (however it has other member functions available), which is used to work with a message's MIME parts. To create a "string building" MIME object, call the :class:`MIME` function without any arguments.

  .. note::

    If you call the :class:`~data.MIME` function **with** an argument in the :doc:`DATA <data>` context then the :doc:`DATA <data>` context's :class:`~data.MIME` object will be created instead.

  .. code-block:: hsl

	$part = MIME();
	$part->setType("multipart/alternative");
	$part->appendPart(MIME()->setType("text/plain")->setBody("*Hello World*"));
	$part->appendPart(MIME()->setType("text/html")->setBody("<strong>Hello World</strong>"));
	echo $part->toString();

  .. note::

    Many of the MIME object's member functions return `this`, allowing them to be called with method chaining.

    .. code-block:: hsl

       echo MIME()->addHeader("Subject", "Hello")->setBody("Hello World")->toString();

  .. function:: MIME.addHeader(name, value)

	  Add a header. The value may be encoded (if needed) and reformatted.

	  :param string name: name of the header
	  :param string value: value of the header
	  :return: this
	  :rtype: MIME

	  .. note::

		If a `Content-Type` header is added, the value of :func:`MIME.setType` is ignored. If a `Content-Transfer-Encoding` header is added no encoding will be done on data added by :func:`MIME.setBody`.

  .. function:: MIME.appendPart(part)

	  Add a MIME part (child) object, this is useful when building a multipart MIME.

	  :param MIME part: a MIME part object
	  :return: this
	  :rtype: MIME

	  .. note::

		The `Content-Type` is not automatically set to `multipart/\*`, this has to be done using :func:`MIME.setType`. The MIME boundary is however automatically created.

  .. function:: MIME.setBody(data)

	  Set the MIME part body content. In case the MIME part has children (multipart) this will be the MIME parts preamble. The data will be Base64 encoded if no `Content-Transfer-Encoding` header is added.

	  :param string data: the data
	  :return: this
	  :rtype: MIME

  .. function:: MIME.setType(type)

	  Set the type field of the `Content-Type` header. The default type is `text/plain`, and the charset is always utf-8.

	  :param string type: the content type
	  :return: this
	  :rtype: MIME

  .. function:: MIME.setBoundary(boundary)

	  Set the MIME boundary for `multipart/\*` messages. The default is to use an UUID.

	  :param string boundary: the boundary
	  :return: this
	  :rtype: MIME

  .. function:: MIME.signDKIM(selector, domain, key, [options])

	  Sign the MIME structure (message) using `DKIM <http://wiki.halon.se/DKIM>`_.

	  :param string selector: selector to use when signing
	  :param string domain: domain to use when signing
	  :param string key: private key to use, either ``pki:X`` or a private RSA key in PEM format.
	  :param array options: options array
	  :return: this
	  :rtype: MIME

	  The following options are available in the options array.

	   * **canonicalization_header** (string) body canonicalization (``simple`` or ``relaxed``). The default is ``relaxed``.
	   * **canonicalization_body** (string) body canonicalization (``simple`` or ``relaxed``). The default is ``relaxed``.
	   * **algorithm** (string) algorithm to hash the message with (``sha1`` or ``sha256``). The default is ``sha256``.
	   * **additional_headers** (array) additional headers to sign in addition to those recommended by the RFC.
	   * **headers** (array) headers to sign. The default is to sign all headers recommended by the RFC.

  .. function:: MIME.toString()

	  Return the created MIME as a string. This function useful for debugging.

	  :return: the MIME as string
	  :rtype: string

  .. function:: MIME.send(sender, recipient, transportid, [options])

	  Put the MIME message (email) into the queue.

	  :param string sender: the sender
	  :param string recipient: the recipient
	  :param string transportid: the transportid
	  :param array options: options array
	  :return: the message id
	  :rtype: string

	  The following options are available in the options array.

	   * **metadata** (array) Add additional metadata to the message (KVP).

	  .. code-block:: hsl

		MIME()
			->addHeader("Subject", "Hello")
			->setBody("Hi, how are you?")
			->send("", "info@example.com", "mailtransport:1");

Misc
----

.. function:: serial()

  The serial number of the installation, this can be used to identify a software instance.

  :return: the serial number
  :rtype: string

.. function:: gethostname()

  The hostname of the installation, this can be used to identify a software instance.

  :return: the hostname
  :rtype: string

.. function:: uuid()

  Return a unique ID.

  :return: a unique ID
  :rtype: string

.. function:: echo

  Print a message to the log.

  .. code-block:: hsl
  	
	echo "Log message";

  .. note::

	`echo` is not a function, therefore do not call it with parentheses, all messages are logged as :func:`syslog` level `debug`, with ``$messageid`` prefixed.

.. function:: syslog(priority, message)

  The syslog function complements the ``echo`` statement by allowing messages with custom priorities to be logged.

  :param priority: message priority
  :type priority: string or number
  :param string message: message
  :rtype: none

  Priority may be any of

  +----------+---+
  | Name     |   |
  +==========+===+
  | emerg    | 0 |
  +----------+---+
  | alert    | 1 |
  +----------+---+
  | crit     | 2 |
  +----------+---+
  | err      | 3 |
  +----------+---+
  | warning  | 4 |
  +----------+---+
  | notice   | 5 |
  +----------+---+
  | info     | 6 |
  +----------+---+
  | debug    | 7 |
  +----------+---+

  It's possible to change the facility of a log message by adding a facility value (see rfc5424).

  .. code-block:: hsl

	syslog(3 + (4<<3), "This is sent as LOG_ERR to LOG_AUTH");

  .. note::

  	If you want your log message to appear when the message log is viewed (as it does with :func:`echo`, you should prefix the message parameter with ``"[$messageid] "``.

.. function:: stat(name, legends)

  Collect statistics based on one or more legend (value). The `name` is the name of the graph (the collection of `legends`). A legend is a value for which the system should collect statistics.

  :param string name: name of the graph
  :param array legends: key value pair of legends
  :rtype: none

  Values stat'ed are available

   * as a line graph (on the graphs and report page)
   * as a pie chart (on the graphs and report page)
   * using the statList and graphFile SOAP API call.
   * using SNMP

  In order for the line graph to work properly, all values should be defined to the stat function on every `stat` call (even if they are not increased).

  .. code-block:: hsl

	  $fam4 = 0; $fam6 = 0;
	  if (in_network($senderip, "0.0.0.0/0")) { $fam4 = 1; } else { $fam6 = 1; }
	  stat("ip-family", ["ipv4" => $fam4, "ipv6" => $fam6]);

  .. note::

	You can only use "a-z0-9.-" in the name and "a-z0-9-" in the legends (legends longer than 19 characters will be truncated on the graph page) when using the stat function. For example, uppercase letters are not allowed.

.. function:: in_network(ip, network)

  Returns true if `ip` is in the subnet of `network`. Both IPv4 and IPv6 are supported.

  :param string ip: ip address
  :param string network: address, subnet or range.
  :return: true if ip is in network
  :rtype: boolean

  .. code-block:: hsl

	in_network("127.0.0.1", "127.0.0.1/8");
	in_network("127.0.0.1", "127.0.0.0-127.255.255.255");
	in_network("127.0.0.1", "127.0.0.1");
	in_network("2001:4860:4860::8888", "2001:4860:4860::/48");

.. function:: inet_ntop(ip)

	Converts an IP from a binary string format (4 char for IPv4 and 16 char for IPv6) to a printable string format (eg `10.0.0.1`). On error `None` is returned.

	:param string ip: the ip in binary string format
	:return: an ip in printable string format
	:rtype: string

.. function:: inet_pton(ip)

	Converts an IP from printable string format (eg `10.0.0.1`) to a binary string format (4 char for IPv4 and 16 char for IPv6). On error `None` is returned.

	:param string ip: the ip in printable format
	:return: an ip in binary string format
	:rtype: string

	.. code-block:: hsl

		$x = unpack("N*", inet_pton($ip));
		if (count($x) == 1)
			$x[0] = $x[0] & 0xffffff00; // mask ipv4 to /24
		if (count($x) == 4)
			$x[3] = 0; // mask ipv6 to /96
		echo inet_ntop(pack("N*", ...$x));

.. function:: rate(namespace, entry, count, interval, [options])

  Check or account for the rate of entry in namespace during the last interval.

  :param string namespace: the namespace
  :param string entry: an entry
  :param number count: the count
  :param number interval: the interval in seconds
  :param array options: options array
  :return: if count is greater than zero, it will increase the rate and return ``true``, or return ``false`` if the limit is exceeded. If count is zero ``0``, it will return the number of items during the last ``interval``.
  :rtype: number

  The following options are available in the options array.

   * **sync** (boolean) Do not synchronize the rate in between nodes in the cluster. The default is ``true``.

  .. code-block:: hsl

	  if (rate("outbound", $saslusername, 3, 60) == false) {
	        Reject("User is only allowed to send 3 messages per minute");
	  }

  .. note::

  	Rates are shared between all contexts, and may also be synchronized in clusters.

.. function:: mail(sender, recipient, subject, body, [options])

  Put a message (email) into the queue.

  :param string sender: the sender
  :param string recipient: the recipient
  :param string subject: the subject
  :param string body: the body
  :param array options: options array
  :return: the message id
  :rtype: string

  The following options are available in the options array.

   * **sender_name** (string) Friendly name of the sender.
   * **recipient_name** (string) Friendly name of the recipient.
   * **serverid** (string) Helps the decision making of where we should send this email.
   * **transportid** (string) Set the transportid to be used with this message.
   * **rawbody** (boolean) Instead of using a template, send body as raw text/plain. The default is ``false``.
   * **headers** (array) Add additional message headers (KVP).
   * **metadata** (array) Add additional metadata to the message (KVP).

  .. code-block:: hsl

	  mail("postmaster@example.com", "support@halon.se", "Lunch", "How about lunch on Friday?");

Protocols
---------

.. function:: smtp_lookup_rcpt(server, sender, recipient, [options])

  Check if sender is allowed to send mail to recipient.

  :param server: array with server settings or mailtransport profile
  :type server: string or array
  :param string sender: the sender (MAIL FROM)
  :param string recipient: the recipient (RCPT TO)
  :param array options: options array
  :return: ``1`` if the command succeeded, ``0`` if the command failed and ``-1`` if an error occurred. The ``extended_result`` option may change this behavior.
  :rtype: number or array

  The following server settings are available in the server array.

   * **host** (string) IP-address or hostname. **required**
   * **port** (number) TCP port. The default is ``25``.
   * **helo** (string) The default is to use the system hostname.
   * **sourceip** (string) Explicitly bind a ``netaddr:X`` or an IP address. The default is to be chosen by the system.
   * **nonlocal_source** (boolean) Allow binding of non-local addresses (BINDANY). The default is ``false``.
   * **saslusername** (string) If specified issue a AUTH LOGIN before MAIL FROM.
   * **saslpassword** (string) If specified issue a AUTH LOGIN before MAIL FROM.
   * **tls** (string) Use any of the following TLS modes; ``disabled``, ``optional``, ``optional_verify``, ``dane``, ``dane_require``, ``require`` or ``require_verify``. The default is ``disabled``.
   * **tls_sni** (string or boolean) Request a certificate using the SNI extension. If ``true`` the connected hostname will be used. The default is not to use SNI (``false``).
   * **tls_protocols** (string) Use one or many of the following TLS protocols; ``SSLv2``, ``SSLv3``, ``TLSv1``, ``TLSv1.1`` or ``TLSv1.2``. Protocols may be separated by ``,`` and excluded by ``!``. The default is ``!SSLv2,!SSLv3``.
   * **tls_ciphers** (string) List of ciphers to support. The default is decided by OpenSSL for each ``tls_protocol``.
   * **tls_verify_host** (boolean) Verify certificate hostname (CN). The default is ``false``.
   * **tls_verify_name** (array) Hostnames to verify against the certificate's CN and SAN.
   * **tls_default_ca** (boolean) Load additional TLS certificates (ca_root_nss). The default is ``false``.
   * **tls_client_cert** (string) Use the following ``pki:X`` as client certificate. The default is to not send a client certificate.
   * **tls_capture_peer_cert** (boolean) If set to true, the peer certificate will be available in the extended results. The default is ``false``.
   * **xclient** (array) Associative array of XCLIENT attributes to send.

  The following options are available in the options array.

   * **extended_result** (boolean) If ``true`` an associative array with ``error_code``, ``error_message``, ``on_rcptto`` and ``tls`` is returned. The default is ``false``.

.. function:: smtp_lookup_auth(server, username, password)

  Try to authenticate the username against a SMTP server.

  :param server: array with server settings or mailtransport profile
  :type server: string or array
  :param string username: username
  :param string password: password
  :return: ``1`` if the authentication succeeded, ``0`` if the authentication failed and ``-1`` if an error occurred.
  :rtype: number

  The following server settings are available in the server array.

   * **host** (string) IP-address or hostname. **required**
   * **port** (number) TCP port. The default is ``25``.
   * **helo** (string) The default is to use the system hostname.
   * **sourceip** (string) Explicitly bind a ``netaddr:X`` or an IP address. The default is to be chosen by the system.
   * **nonlocal_source** (boolean) Allow binding of non-local addresses (BINDANY). The default is ``false``.
   * **tls** (string) Use any of the following TLS modes; ``disabled``, ``optional``, ``optional_verify``, ``dane``, ``dane_require``, ``require`` or ``require_verify``. The default is ``disabled``.
   * **tls_sni** (string or boolean) Request a certificate using the SNI extension. If ``true`` the connected hostname will be used. The default is not to use SNI (``false``).
   * **tls_protocols** (string) Use one or many of the following TLS protocols; ``SSLv2``, ``SSLv3``, ``TLSv1``, ``TLSv1.1`` or ``TLSv1.2``. Protocols may be separated by ``,`` and excluded by ``!``. The default is ``!SSLv2,!SSLv3``.
   * **tls_ciphers** (string) List of ciphers to support. The default is decided by OpenSSL for each ``tls_protocol``.
   * **tls_verify_host** (boolean) Verify certificate hostname (CN). The default is ``false``.
   * **tls_verify_name** (array) Hostnames to verify against the certificate's CN and SAN.
   * **tls_default_ca** (boolean) Load additional TLS certificates (ca_root_nss). The default is ``false``.

.. function:: ldap_search(profile, lookup, [override])

  Query an LDAP server for lookup and return all LDAP entries found.

  :param string profile: ldap profile
  :param any lookup: if lookup is a string value it will be inserted into the ldap query replacing ``%s`` (ldapescaped) or ``%x`` (raw, dangerous). If lookup is an array it will replace items (ldapsecaped) as $1, $2...
  :param array override: override array
  :return: an array with LDAP entries or ``-1`` if an error occurred.
  :rtype: array or number

  The following overrides are available in the override array.

   * **host** (string) LDAP URI (ldap:// or ldaps://).
   * **username** (string) LDAP username.
   * **password** (string) LDAP password.
   * **base** (string) LDAP base.
   * **query** (string) LDAP query (unescaped).
   * **tls_default_ca** (boolean) Load additional TLS certificates (ca_root_nss). The default is ``true``.
   * **tls_verify_peer** (boolean) Verify peer certificate. The default is ``true``.

.. function:: ldap_bind(profile, username, password, [override])

  Try to bind (authenticate) against an LDAP server.

  :param string profile: ldap profile
  :param string username: LDAP username
  :param string password: LDAP password
  :param array override: override array
  :return: ``1`` if the authentication succeeded, ``0`` if the authentication failed and ``-1`` if an error occurred.
  :rtype: number

  The following overrides are available in the override array.

   * **host** (string) LDAP URI (ldap:// or ldaps://).
   * **tls_default_ca** (boolean) Load additional TLS certificates (ca_root_nss). The default is ``true``.
   * **tls_verify_peer** (boolean) Verify peer certificate. The default is ``true``.

.. function:: radius_authen(options, username, password, [vendorstrings])

  Authenticate against a RADIUS server.

  :param array options: options array
  :param string username: username
  :param string password: password
  :param array vendorstrings: array of vendor strings
  :return: ``1`` if the authentication succeeded, ``0`` if the authentication failed and ``-1`` if an error occurred.
  :rtype: number

  The following options are available in the options array.

   * **host** (string) IP-address or hostname of the RADISU server. **required**
   * **secret** (string) The secret. **required**
   * **port** (number) TCP port. The default is ``1812``.
   * **timeout** (number) Timeout in seconds. The default is ``5`` seconds.
   * **clientip** (string) The IP-address of the client (remote IP).
   * **retry** (number) The retry count is ``3``.

   Vendor strings must be strings and must be registered as ID 33234 (`Halon Security's Enterprise Number <http://www.iana.org/assignments/enterprise-numbers>`_)

.. function:: tacplus_authen(options, username, password)

  Authenticate against a TACACS+ server (e.g. Cisco Secure ACS).

  :param array options: options array
  :param string username: username
  :param string password: password
  :return: ``1`` if the authentication succeeded, ``0`` if the authentication failed and ``-1`` if an error occurred.
  :rtype: number

  The following options are available in the options array.

   * **host** (string) IP-address or hostname of the TACACS+ server. **required**
   * **secret** (string) The secret. **required**
   * **port** (number) TCP port. The default is ``49``.
   * **timeout** (number) Timeout in seconds. The default is ``5`` seconds.
   * **clientip** (string) The IP-address of the client (remote IP).

.. function:: tacplus_author(options, username, avpair)

  Send an authorization request to a TACACS+ server.

  :param array options: options array
  :param string username: username
  :param array avpair: an array of avpairs
  :return: an array with avpairs entries if the authorization succeeded, ``0`` if the authorization failed and ``-1`` if an error occurred.
  :rtype: array or number

  The following options are available in the options array.

   * **host** (string) IP-address or hostname of the TACACS+ server. **required**
   * **secret** (string) The secret. **required**
   * **port** (number) TCP port. The default is ``49``.
   * **timeout** (number) Timeout in seconds. The default is ``5`` seconds.
   * **clientip** (string) The IP-address of the client (remote IP).

String
------

.. function:: chr(number)

  Returns ASCII character from a number. This function complements :func:`ord`.

  :param number number: the ASCII number
  :return: ASCII character
  :rtype: string

.. function:: ord(character)

  Return ASCII value of a character. This function complements :func:`chr`.

  :param string character: the ASCII character
  :return: the ASCII value
  :rtype: number

.. function:: str_repeat(s, n)

  Returns the string s repeated n times.

  :param string s: the input string
  :param number n: the string multiplier
  :return: s repeated n times
  :rtype: string

.. function:: str_replace(search, replace, subject)

  Returns the string subject with the string search replace with replace.

  :param string search: the search string
  :param string replace: the replace string
  :param string subject: the string acted upon
  :return: subject with searched replaced with replace
  :rtype: string

.. function:: strlen(s)

  Returns the length of the string s.

  :param string s: the input string
  :return: the length of s
  :rtype: number

.. function:: strpos(s, find, [offset = 0])

  Return the position (starting from zero) of the first occurrence of find in s (starting from the offset). If the find is **not** found -1 is returned.

  :param string s: the input string
  :param string find: the string to look for
  :param number offset: the offset from the start
  :return: the position where find is found
  :rtype: number

.. function:: strrpos(s, find, [offset = 0])

  Return the position (starting from zero) of the last occurrence of find in s searching backward (starting from the offset relative to the end). If the find is **not** found -1 is returned.

  :param string s: the input string
  :param string find: the string to look for
  :param number offset: the offset from the end
  :return: the position where find is found
  :rtype: number

.. function:: strtolower(s)

  Returns s with all US-ASCII character to lowercased.

  :param string s: the input string
  :return: the string lowercased
  :rtype: string

.. function:: strtoupper(s)

  Returns s with all US-ASCII character uppercased.

  :param string s: the input string
  :return: the string uppercased
  :rtype: string

.. function:: substr(s, [[start = 0], len])

  Return the substring of s.

  :param string s: the input string
  :param number start: the start position
  :param number len: the length limit if given
  :return: the substring
  :rtype: string

.. function:: trim(s)

  Returns s with whitespace characters removed from the start and end of the string.

  :param string s: the input string
  :return: the trimmed string
  :rtype: string

.. function:: pcre_match(pattern, subject)

  PCRE matching in subject.

  :param string pattern: the regular expression
  :param string subject: the string to match against
  :return: returns matches, if no result is found an empty array is returned.
  :rtype: array

  Perl compatible regular expression data matching and extraction, requires capture groups. All modifiers supported by ``=~`` operator are available.

  .. note::

	  Use :ref:`raw strings <rawstring>` so you don't have to escape the pattern.

  .. seealso::

	  For matching only the :ref:`regular expression <regex>` operator can be used.

.. function:: pcre_match_all(pattern, subject)

  The implementation is identical to :func:`pcre_match` except the return type.

  :param string pattern: the regular expression
  :param string subject: the string to match against
  :return: returns multiple results group by capture groups, and matched result.
  :rtype: array

.. function:: pcre_quote(string)

  Quote all metacharacters which has special meaning in a regular expression.

  :param string string: the string
  :return: a quoted string
  :rtype: string

.. function:: pcre_replace(pattern, replace, subject, [limit = 0])

  Perl compatible regular expression data matching and replacing

  :param string pattern: the regular expression to match
  :param any replace: the pattern to replace as string or a callback function
  :param string subject: the string acted upon
  :param number limit: max occurrences to replace (`0` equals `unlimited`)
  :return: return subject with the replacements done
  :rtype: string

  In `replace` matches are available using ``$0`` to ``$n``. ``$0`` will be the entire match, and ``$1`` (and forward) each match group.

  The replace function should take one argument (array of values ``[$0, $n...]``) and return a string value.

.. code-block:: hsl

	echo pcre_replace("\\[link](.*?)\\[/link]",
	        "<a href=\"$1\">$1</a>",
			        "[link]http://halon.se[/link]");
	// <a href="http://halon.se">http://halon.se</a>

	echo pcre_replace("\\d", "($0)", "foo1bar2baz");
	// foo(1)bar(2)baz

	// "ucfirst()"
	echo pcre_replace(''\b[a-z]'', function ($i) { return strtoupper($i[0]); }, "hello world");
	// Hello World

Socket
------

.. class:: Socket(family, type)

  The Socket class allows POSIX like socket(2) code. A socket resource is created for each Socket instance, this resource is automatically garbage collected (closed) once the object is destroyed.

  :param string family: address family either ``AF_INET`` or ``AF_INET6``
  :param string type: socket type either ``SOCK_STREAM`` (TCP) or ``SOCK_DGRAM`` (UDP)

  .. code-block:: hsl

	$socket = Socket("AF_INET", "SOCK_STREAM");
	$socket->close();

	$socket2 = Socket(Socket::AF($address), "SOCK_STREAM");
	$socket2->close();

  .. function:: Socket.bind(address, [port, [options]])

	  Bind the socket to `address` and `port`. The address must match the Sockets address family.

	  :param string address: address to bind
	  :param number port: port to bind
	  :param array options: options array
	  :return: this
	  :rtype: Socket or None

	  The following options are available in the options array.

	   * **nonlocal** (boolean) Allow binding of a nonlocal source address (BINDANY). The default is ``false``.

  .. function:: Socket.close()

	  Close the socket and destroy the internal socket resource.

	  :return: this
	  :rtype: Socket or None

	  .. note::

		Sockets are automatically garbage collected (closed). However you may want to explicitly call close.

  .. function:: Socket.connect(address, port)

	  Connect the socket to `address` and `port`. The address must match the Sockets address family.

	  :param string address: address to connect to
	  :param number port: port to connect to
	  :return: this
	  :rtype: Socket or None

  .. function:: Socket.errno()

	  Get the latest errno returned from the underlying POSIX socket API.

	  :return: errno
	  :rtype: number

  .. function:: Socket.recv(len, [flags])

	  Receive data on socket.

	  :param number len: up to len bytes to receive
	  :param string flags: flags to control the behaviour
	  :return: data
	  :rtype: string or None

	  Flags may be any of, the default is no posix recv(3) flag.

	  +--------------+------------------------------------------+
	  | Name         | Behaviour                                |
	  +==============+==========================================+
	  | MSG_PEEK     | peek at incoming message                 |
	  +--------------+------------------------------------------+
	  | MSG_WAITALL  | wait for full request or error           |
	  +--------------+------------------------------------------+
	  | MSG_DONTWAIT | do not block                             |
	  +--------------+------------------------------------------+

  .. function:: Socket.send(data)

	  Send data on socket.

	  :param string data: data to send
	  :return: bytes sent
	  :rtype: number or None

  .. function:: Socket.settimeout(timeout)

	  Set the timeout for socket operations.

	  :param number timeout: timeout in seconds. The default is no timeout.
	  :return: this
	  :rtype: Socket

  .. function:: Socket.shutdown(how)

	  Shutdown the socket for receiving, sending or both.

	  :param string how: how to shutdown either ``SHUT_RD``, ``SHUT_WR`` or ``SHUT_RDWR``.
	  :return: this
	  :rtype: Socket or None

	  .. note::

		Sockets are automatically closed.

  .. staticmethod:: AF(address)

	  Return the AF family of an address (either ``AF_INET`` or ``AF_INET6``). A utility function helpful when constructing a :class:`Socket` class.

	  :param string address: address
	  :return: AF family
	  :rtype: String or None

.. class:: TLSSocket(socket, options)

  The TLSSocket class allows OpenSSL like SSL(3) code. The TLSSocket class takes a connected :class:`Socket` instance (SOCK_STREAM) and encapsulates any read and writes in TLS/SSL.

  :param Socket socket: a socket
  :param array options: options array

  The following options are available in the options array.

   * **tls_protocols** (string) Use one or many of the following TLS protocols; ``SSLv2``, ``SSLv3``, ``TLSv1``, ``TLSv1.1`` or ``TLSv1.2``. Protocols may be separated by ``,`` and excluded by ``!``. The default is ``!SSLv2,!SSLv3``.
   * **tls_ciphers** (string) List of ciphers to support. The default is decided by OpenSSL for each ``tls_protocol``.
   * **tls_verify_name** (array) Hostnames to verify against the certificate's CN and SAN.
   * **tls_verify_ca** (boolean) Verify certificate against known CAs. The default is ``false``.
   * **tls_default_ca** (boolean) Load additional TLS certificates (ca_root_nss). The default is ``false``.
   * **tls_sni** (string) Request a certificate using the SNI extension. The default is not to use SNI.
   * **tls_client_cert** (string) Use the following ``pki:X`` as client certificate. The default is to not send a client certificate.

  .. note::

	By default, no certificate nor hostname validation is done.

  .. function:: TLSSocket.handshake()

	  Perform the TLS/SSL handshake. If the handshake fails or the validation fails none is returned.

	  :return: this
	  :rtype: TLSSocket or None

  .. function:: TLSSocket.recv(len)

	  Receive data on TLS/SSL socket. This function may perform an implicit handshake.

	  :param number len: up to len bytes to recv
	  :return: data
	  :rtype: string or None

  .. function:: TLSSocket.send(data)

	  Send data on TLS/SSL socket. This function may perform an implicit handshake.

	  :param string data: data to send
	  :return: bytes sent
	  :rtype: number or None

  .. function:: TLSSocket.shutdown()

	  Shut down the TLS/SSL connection. This function may need to be called multiple times. See SSL_shutdown(3) for details.

	  :return: shutdown status
	  :rtype: number or None

  .. function:: TLSSocket.errno()

	  Get the latest errno returned from the underlying OpenSSL SSL(3) socket API.

	  :return: errno
	  :rtype: number

  .. function:: TLSSocket.getpeercert([options])

	  Get the peer certificate (X.509) given during the handshake as an associative array.

	  :param array options: options array
	  :return: X.509 certificate data
	  :rtype: array

	  The following options are available in the options array.

	   * **fingerprint** (string) Generate the fingerprint of the certificate using one of the following hash function (``md5``, ``sha1``, ``sha256`` or ``sha512``). The default no hashing.

	  The following items are available in the result.

	   * **subject** (array) The subject, if there are duplicate attribute types (eg. C or CN) the attribute value will be an array instead
	   * **issuer** (array) The issuer, if there are duplicate attribute types (eg. C or CN) the attribute value will be an array instead
	   * **subject_alt_name** (array) The subject alt names ``DNS`` items
	   * **version** (number) The version of the X.509 certificate
	   * **serial_number** (string) The serial number in HEX
	   * **not_valid_before** (number) The start date of the certificate (in unix time)
	   * **not_valid_after** (number) The end date of the certificate (in unix time)
	   * **fingerprint** (string) The certificate fingerprint (if requested)

	  .. note::

		Example output (using :func:`json_encode` with pretty print)::

			{
				"subject": {
					"C": "US",
					"ST": "California",
					"L": "Mountain View",
					"O": "Google Inc",
					"CN": "mail.google.com"
				},
				"issuer": {
					"C": "US",
					"O": "Google Trust Services",
					"CN": "Google Internet Authority G3"
				},
				"subject_alt_name": {
					"DNS": [
						"mail.google.com",
						"inbox.google.com"
					]
				},
				"version": 2,
				"serial_number": "5d8bca2821d49564",
				"not_valid_before": 1511950612,
				"not_valid_after": 1519205880
			}
