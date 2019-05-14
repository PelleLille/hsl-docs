Standard library
================

Functions which are documented in this chapter are considered `core` functions hence are available in all `contexts`. Functions in the standard library may be recognized by the fact that they are all in lowercase.

* **Array** :func:`array_filter` :func:`array_find` :func:`array_includes` :func:`array_join` :func:`array_keys` :func:`array_map` :func:`array_range` :func:`array_reduce` :func:`array_reverse` :func:`array_sort`
* **Cryptographic** :func:`aes_decrypt` :func:`aes_encrypt` :func:`hmac_md5` :func:`hmac_sha1` :func:`hmac_sha2` :func:`md5` :func:`sha1` :func:`sha2` :func:`hash` :func:`rsa_sign` :func:`rsa_verify` :func:`ed25519_sign` :func:`ed25519_verify` :func:`pkcs7_sign` :func:`random_bytes` :func:`random_number` :func:`crypt`
* **Data types** :func:`length` :func:`array` :func:`boolean` :func:`number` :func:`string` :func:`is_array` :func:`is_boolean` :func:`is_function` :func:`is_number` :func:`is_object` :func:`is_string` :func:`isset` :func:`unset`
* **Date and time** :func:`executiontime` :func:`sleep` :func:`strftime` :func:`strptime` :func:`time` :func:`timelocal` :func:`uptime`
* **DNS** :func:`dns_query` :func:`domain_includes` :func:`idna_encode` :func:`idna_decode`
* **Encodings and JSON** :func:`base64_encode` :func:`base64_decode` :func:`csv_decode` :func:`json_encode` :func:`json_decode` :func:`pack` :func:`unpack`
* **File and HTTP** :class:`File` :func:`http`
* **Mail** :func:`header_addresslist_extract` :func:`header_dkim_decode` :func:`xtext_encode` :func:`xtext_decode` :func:`dnsbl` :func:`spf_query` :func:`globalview`
* **Mathematical** :func:`abs` :func:`ceil` :func:`floor` :func:`log` :func:`pow` :func:`round` :func:`sqrt`
* **MIME** :class:`MIME`
* **Misc** :func:`serial` :func:`gethostname` :func:`uuid` :func:`syslog` :func:`stat` :func:`inet_includes` :func:`inet_ntop` :func:`inet_pton` :func:`inet_reverse` :func:`rate` :func:`mail`
* **Protocols** :func:`smtp_lookup_rcpt` :func:`smtp_lookup_auth` :func:`ldap_search` :func:`ldap_bind` :class:`LDAP`
* **String** :func:`chr` :func:`ord` :func:`str_repeat` :func:`str_replace` :func:`str_find` :func:`str_rfind` :func:`str_lower` :func:`str_upper` :func:`str_slice` :func:`str_split`
* **Regular expression** :func:`pcre_match` :func:`pcre_match_all` :func:`pcre_quote` :func:`pcre_replace`
* **Socket** :class:`Socket` :class:`TLSSocket` :class:`X509`

Array
-----

.. function:: array_filter(callback, array)

  Returns the filtered items from the array using a callback.

  :param function callback: the callback
  :param array array: the array
  :return: array of filtered values, keys are preserved
  :rtype: array

  The callback function should take one argument (value) and return a boolean value.

  .. code-block:: hsl

	  array_filter(function ($x) { return $x % 2 == 0; }, [0, 1, 2, 3]); // even values
	  array_filter(is_number, [0, "Hello World", 2]);

.. function:: array_find(callback, array)

  Return the first element that matches in the array.

  :param function callback: the callback
  :param array array: the array
  :return: the value if found
  :rtype: any

  The callback function should take one argument (value) and return a boolean value.

  .. code-block:: hsl

	  array_find(function ($x) { return $x["id"] === 2; }, [["id" => 1, "name" => "a"], ["id" => 2, "name" => "b"]]); // ["id"=>2,"name"=>"b"]

.. function:: array_includes(needle, array)

  Returns true if needle is found in the array.

  :param any needle: the value to match or a callback function
  :param array array: the array
  :return: true if needle is found
  :rtype: boolean

  The callback function should take one argument (value) and return a boolean value. If the needle is not a function, it will be matched using the strict comparison operator (``===``).

  .. code-block:: hsl

	  array_includes(function ($x) { return $x === 2; }, [0, 1, 2, 3]); // true
	  array_includes(false, [0, none, ""]); // false

.. function:: array_join(array, [separator])

  Join the elements in the array with a separator returning a string

  :param array array: the array
  :param string separator: the separator
  :return: a string from an array
  :rtype: string

  .. seealso::
	  To split a string to an array, see :func:`str_split`.

.. function:: array_keys(array)

  Returns the keys in the array.

  :param array array: the array
  :return: array's keys
  :rtype: array

.. function:: array_map(callback, array)

  Returns values from the array with the callback applied.

  :param function callback: the callback
  :param array array: the array
  :return: array of values, keys are preserved
  :rtype: array

  The function should take one argument (value) and return a value.

  .. code-block:: hsl

	  array_map(function ($x) { return $x * 2; }, [0, 1, 2, 3]); // double values

.. function:: array_range(start, stop, [step = 1])

  Returns an array from a numeric range (half-open) with the given steps.

  :param number start: the first number
  :param number stop: the last number (not included)
  :param number step: the step between numbers
  :return: an array with numbers
  :rtype: array

  .. code-block:: hsl

	  foreach (range(0, 9) as $i) // 0,1,2,..,8
		  echo $i;
  
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

Cryptographic
-------------

.. function:: aes_decrypt(message, key, mode, [options])

  Decrypt a message using AES.

  :param string message: the message to decrypt
  :param string key: the key as raw bytes (no padding is done)
  :param string mode: the block cipher mode of operation (``ecb`` or ``cbc``)
  :param array options: options array
  :return: the message decrypted
  :rtype: string or none (on error)

  The following options are available in the options array.

   * **iv** (string) The initialization vector as bytes (16 bytes for ``cbc``).
   * **padding** (boolean) Use PKCS7 padding. The default is ``true``.

  .. note::

	The key length must be either 16 bytes for AES-128, 24 bytes for AES-192 or 32 bytes for AES-256. No NUL bytes padding nor truncation is done on either the key or iv. The example below shows how to do manual padding.

	.. code-block:: hsl

		$message = aes_decrypt(
					$encrypted,
					pack("a32", "short aes-256 key"),
					"cbc",
					["iv" => pack("x16")]
				);

.. function:: aes_encrypt(message, key, mode, [options])

  Encrypt a message using AES.

  :param string message: the message to encrypt
  :param string key: the key as raw bytes (no padding is done)
  :param string mode: the block cipher mode of operation (``ecb`` or ``cbc``)
  :param array options: options array
  :return: the message encrypted
  :rtype: string or none (on error)

  The following options are available in the options array.

   * **iv** (string) The initialization vector as bytes (16 bytes for ``cbc``).
   * **padding** (boolean) Use PKCS7 padding. The default is ``true``.

  .. note::

	The key length must be either 16 bytes for AES-128, 24 bytes for AES-192 or 32 bytes for AES-256. No NUL bytes padding nor truncation is done on either the key or iv. The example below shows how to do manual padding.

	.. code-block:: hsl

		$encrypted = aes_encrypt(
					$message,
					pack("a32", "short aes-256 key"),
					"cbc",
					["iv" => pack("x16")]
				);

.. function:: hmac_md5(key, message)

  Return the HMAC MD5 hash of message with the key.

  :param string key: the HMAC key
  :param string message: the value to hash
  :return: the hash value hex encoded
  :rtype: string

.. function:: hmac_sha1(key, message)

  Return the HMAC SHA1 hash of message with the key.

  :param string key: the HMAC key
  :param string message: the value to hash
  :return: the hash value hex encoded
  :rtype: string

.. function:: hmac_sha2(key, message, hashsize)

  Return the HMAC SHA2 hash of message with the key.

  :param string key: the HMAC key
  :param string message: the value to hash
  :param number hashsize: the hash size (must be 256 or 512)
  :return: the hash value hex encoded
  :rtype: string

.. function:: md5(message)

  Return the MD5 hash of message.

  :param string message: the value to hash
  :return: the hash value hex encoded
  :rtype: string

.. function:: sha1(message)

  Return the SHA1 hash of message.

  :param string message: the value to hash
  :return: the hash value hex encoded
  :rtype: string

.. function:: sha2(message, hashsize)

  Return the SHA2 hash of message.

  :param string message: the value to hash
  :param number hashsize: the hash size (must be 256 or 512)
  :return: the hash value hex encoded
  :rtype: string

.. function:: hash(message)

  Return the numeric hash value of the message. The hash value is same for equal messages.

  :param string message: the value to hash
  :return: the hash value
  :rtype: number

.. function:: rsa_sign(message, privatekey, [options])

  RSA sign a message digest using a hash function.

  :param string message: the message to sign
  :param string privatekey: the private key
  :param array options: options array
  :return: the message signature
  :rtype: string or none (on error)

  The following options are available in the options array.

   * **hash** (string) The hash method to use (``md5``, ``sha1``, ``sha256`` or ``sha512``). The default is ``sha256``.
   * **format** (string) The private key format to use ``PrivateKeyInfo`` (PKCS#8) or ``RSAPrivateKey``. The default is ``RSAPrivateKey``.
   * **pem** (boolean) If the private key is in PEM format or raw bytes. The default is ``false``.
   * **id** (boolean) If the private key is in configuration "pki:X" format. The default is ``false``.

.. function:: rsa_verify(message, signature, publickey, [options])

  RSA verify a message digest using a hash function. On error the function return none.

  :param string message: the message to verify
  :param string signature: the signature for the message as raw bytes
  :param string publickey: the public key
  :param array options: options array
  :return: if the signature verifies
  :rtype: boolean or none (on error)

  The following options are available in the options array.

   * **hash** (string) The hash method to use (``md5``, ``sha1``, ``sha256`` or ``sha512``). The default is ``sha256``.
   * **format** (string) The public key format to use ``SubjectPublicKeyInfo`` or ``RSAPublicKey``. The default is ``RSAPublicKey``.
   * **pem** (boolean) If the public key is in PEM format or raw bytes. The default is ``false``.
   * **id** (boolean) If the public key is in configuration "pki:X" format. The default is ``false``.

.. function:: ed25519_sign(message, privatekey)

  ED25519 sign a message.

  :param string message: the message to sign
  :param string privatekey: the private key as raw bytes
  :return: the message signature
  :rtype: string or none (on error)

.. function:: ed25519_verify(message, signature, publickey)

  ED25519 verify a message.

  :param string message: the message to sign
  :param string signature: the signature as raw bytes
  :param string publickey: the private key as raw bytes
  :return: if the signature verifies
  :rtype: boolean or none (on error)

.. function:: pkcs7_sign(message, certificate, [options])

  PKCS7 sign (S/MIME) a message.

  :param string message: the message to sign
  :param string certificate: the certificate and privatekey to use (PEM format)
  :param array options: options array
  :return: the message signature
  :rtype: string or none (on error)

  The following options are available in the options array.

   * **id** (boolean) If the certificate is in the configuration "pki:X" format. The default is ``false``.
   * **detached** (boolean) If the signature should be detached (not include the message itself). The default is ``true``.

  If the certificate argument contains multiple certificates (intermediates) they will be included in the signature as well.

.. function:: random_bytes(bytes)

  Return a string of random bytes (at most 1MiB).

  :param number bytes: number of bytes to return
  :return: random bytes
  :rtype: string

.. function:: random_number([first, last])

  Return a random integer between first and last (inclusive) or a random double (decimal) between 0 and 1 (inclusive).

  :param number first: first possible number
  :param number last: last possible number
  :return: the random number
  :rtype: number

.. function:: crypt(key, salt)

  Uses the underlying operating system's ``crypt()`` function.

  :param string key: the user's typed password
  :param string salt: the salt
  :return: the encrypted string
  :rtype: string

  .. code-block:: hsl
  
    if (crypt($password, $encryptedpassword) === $encryptedpassword)
      echo "match";

Data types
----------

.. function:: length(value)

  Return the length of an array (items) or a string (characters). For all other datatypes `none` is returned.

  :param any value: the value
  :return: the length
  :rtype: number or none

.. function:: array([...args])

  This function creates an array.

  :param any ....args: the input
  :return: an array
  :rtype: array

  .. note::

	`array` is not a function, it's a language construct to create an :ref:`array <arraytype>` type. It's an alias for the short array syntax ``[]``.

.. function:: boolean(value)

  This function converts the input of value to the boolean type (according to the :ref:`truthiness <truthtable>`) table.

  :param any value: the input
  :return: a boolean
  :rtype: boolean

.. function:: number(value)

  This function converts the input of value to the number type. Decimal and hexadecimal (`Ox`) numbers are supported. If the input contains an invalid number as string or type ``0`` is returned.

  :param any value: the input
  :return: a number
  :rtype: number

.. function:: string(value)

  This function converts the input of value to the string type, hence converting it to its string representation.

  :param any value: the input
  :return: a string
  :rtype: string

.. function:: is_array(value)

  Returns true if the type of value is an array.

  :param any value: the input
  :return: the result
  :rtype: boolean

.. function:: is_boolean(value)

  Returns true if the type of value is a boolean.

  :param any value: the input
  :return: the result
  :rtype: boolean

.. function:: is_function(value)

  Returns true if the type of value is a function.

  :param any value: the input
  :return: the result
  :rtype: boolean

.. function:: is_number(value)

  Returns true if the type of value is a number.

  :param any value: the input
  :return: the result
  :rtype: boolean

.. function:: is_object(value)

  Returns true if the type of value is an object.

  :param any value: the input
  :return: the result
  :rtype: boolean

.. function:: is_string(value)

  Returns true if the type of value is a string.

  :param any value: the input
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

.. function:: sleep(seconds)

  Pause the code execution for x seconds.

  :param number seconds: the number of seconds to sleep
  :return: the time slept in seconds (with decimals)
  :rtype: number

.. function:: strftime(format, [time], [options])

  Format according to the `strftime <http://www.freebsd.org/cgi/man.cgi?query=strftime>`_ manual.

  :param string format: the format string
  :param number time: the default is current time without timezone
  :param array options: options array
  :return: the time formatted (max length 100)
  :rtype: string

  The following options are available in the options array.

  * **local** (boolean) Expect the time to be in the current local timezone. The default is ``true``.

  .. code-block:: hsl

	 echo strftime("%H:%M:%S"); // prints current time eg "13:58:38"

.. function:: strptime(datestring, format, [options])

  Parse a date string according to the `strftime <http://www.freebsd.org/cgi/man.cgi?query=strftime>`_ manual with the time without timezone.

  :param string datestring: the date string
  :param string format: the format string
  :param array options: options array
  :return: the time in seconds
  :rtype: number

  The following options are available in the options array.

  * **local** (boolean) Expect the time to be in the current local timezone. The default is ``true``.

  .. code-block:: hsl

	 echo strptime("13:58:38", "%H:%M:%S"); // prints time of today at "13:58:38"

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

.. function:: dns_query(host, [options])

  Query for DNS records of a hostname.

  :param string host: the host
  :param array options: options array
  :return: the result
  :rtype: array

  The following options are available in the options array.

  * **type** (string) Query type (one of ``a``, ``aaaa``, ``mx``, ``txt``, ``cname``, ``ns`` or ``ptr``). The default is to query for ``a`` records.
  * **timeout** (number) Query timeout in seconds. The default is ``5``.
  * **servers** (array) List of resolvers. The default is the system wide.

  An array with either ``result`` or ``error`` in set in an associative array. ``dnssec`` is always included. ``result`` is the list of results and ``error`` is the string representation of `rcode` or `h_errno`.

  .. code-block:: hsl

	echo dns_query("nxdomain.halon.se");
	// ["error"=>"NXDOMAIN","dnssec"=>false]

	echo dns_query("halon.se");
	// ["result"=>[0=>"54.152.237.238"],"dnssec"=>false]

	echo dns_query(inet_reverse("8.8.8.8"), ["type" => "ptr"]);
	// ["result"=>[0=>"google-public-dns-a.google.com"],"dnssec"=>false]

	echo dns_query(inet_reverse("12.34.56.78", "dnsbl.example.com"));
	// ["result"=>[0=>"127.0.0.1"],"dnssec"=>false]

.. function:: domain_includes(subdomain, domain)

  Test if subdomain is a subdomain of domain. If the domain starts with a dot ``.`` it must be a subdomain of domain, hence it will **not** even if `subdomain == domain`.

  :param string subdomain: the subdomain
  :param string domain: the domain
  :return: if subdomain is a subdomain of domain
  :rtype: boolean

  .. code-block:: hsl

	domain_includes("www.halon.io", "halon.io"); // true
	domain_includes("halon.io", "halon.io"); // true
	domain_includes("www.halon.io", ".halon.io"); // true
	domain_includes("halon.io", ".halon.io"); // false

.. function:: idna_encode(domain)

  IDNA encode a domain (to punycode). On error ``None`` is returned.

  :param string domain: a unicode domain
  :return: the punycode (ASCII) domain
  :rtype: string

  .. code-block:: hsl

	echo idna_encode("fußball.example"); // xn--fuball-cta.example

.. function:: idna_decode(domain)

  IDNA decode a domain (to unicode). On error ``None`` is returned.

  :param string domain: a punycode (ASCII) domain
  :return: the unicode domain
  :rtype: string

  .. code-block:: hsl

	echo idna_decode("xn--fuball-cta.example"); // fußball.example

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

.. function:: csv_decode(string, [options])

  Parse CSV data as string.

  :param string string: CSV formated string
  :param array options: options array
  :return: an array of data
  :rtype: array

  The following options are available in the options array.

   * **delimiter** (string) The format separator. The default is ``,``.
   * **header** (boolean) If the CSV data includes a header. The default is ``true``.
   * **schema** (array) Use a schema to convert columns to types.

  The schema should be of the format of being an array keyed on the column name.

  ::

    [
      "columnname" => [
          "type" => "string" or "boolean" or "number",
          "nullable" => true or false or [ "", "NULL", ... ],
          "true" => [ "True", ... ],
          "false" => [ "False", ... ],
      ],
      ...
    ]

  If the column is nullable either set ``nullable`` to ``true`` (to treat empty strings as `none`) or set ``nullable`` to an array of values to treat as `none` (eg. ``["NULL"]``). Likewise the boolean type has a ``true`` and ``false`` property for truthy and falsy values. The default is ``["true"]`` and ``["false"]`` (all lowercase).

  .. code-block:: hsl

    echo csv_decode("enabled\nyes\nno", ["schema" => [
                    "enabled" => ["type" => "boolean", "true" => ["yes"], "false" => ["no"]]
                ]]);
    // [0=>["enabled"=>true],1=>["enabled"=>false]]

  .. note::

	  It's possible to import CSV data at compile time to a variable using the :ref:`import <data_import>` statement.

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

.. function:: json_decode(string, [options])

  Decodes a JSON string into a HSL data type.

  :param string string: JSON serialized data
  :param array options: options array
  :return: the decoded string as the correct type, and on errors ``None`` is returned
  :rtype: any

  The following options are available in the options array.

   * **allow_comments** (boolean) Allow and ignore comments. The default is ``false``.

  The following translations are done (JSON to HSL).

  * **object** to **associative array** (is_array)
  * **array** to **array** (is_array)
  * **string** to **string** (is_string)
  * **number** to **number** (is_number)
  * **true** to ``true`` (is_boolean)
  * **false** to ``false`` (is_boolean)
  * **null** to **none**

.. note::

  It's possible to import JSON data at compile time to a variable using the :ref:`import <data_import>` statement.

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

.. class:: File

  This class allows low level file access. A file resource is created for each File instance, this resource is automatically garbage collected (closed) once the object is destroyed.

  .. function:: File.constructor(filename)

    Open a virtual file from the configuration. 

    :param string filename: the file name

    .. code-block:: hsl

  	$file = File("myfile.txt");
  	while ($data = $file->read(8192))
	  	echo $data;

  .. function:: File.close()

	  Close the file and destroy the internal file resource.

	  :return: none
	  :rtype: None

	  .. note::

		Files are automatically garbage collected (closed). However you may want to explicitly call close.

  .. function:: File.read([length])

    Read data from file. On EOF an empty string is returned. On error ``None`` is returned.

    :param number length: bytes to read
    :return: data
    :rtype: string or None

	  If no length is given, all the remaning data until EOF will be read in one operation.

  .. function:: File.readline()

	  Read a line from file (without the CRLF or LF). On EOF or error ``None`` is returned.

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

  .. function:: File.getPath()

	  Get the path of a file. If no path information is available ``None`` is returned.

	  :return: path
	  :rtype: string or None

  .. staticmethod:: String(data)

	  Return a File resource containing the data.

	  :param string data: the content
	  :return: A file resource
	  :rtype: File or None

  .. code-block:: hsl

	$file = File::String("Hello\nWorld");
	echo $file->readline(); // "Hello"

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
   * **sourceip** (string) Explicitly bind an IP address. The default is to be chosen by the system.
   * **sourceipid** (string) Explicitly bind an IP address ID. The default is to be chosen by the system.
   * **method** (string) Request method. The default is ``GET`` unless ``POST`` data is sent.
   * **headers** (array) An array of additional HTTP headers as strings. 
   * **response_headers** (boolean) Return the full request, including response headers (regardless of HTTP status). The default is ``false``.
   * **redirects** (number) Specify the number of 304 redirects to follow (use ``-1`` for unlimited). The default is ``0`` (not to follow redirects).
   * **tls_verify_peer** (boolean) Verify peer certificate. The default is ``true``.
   * **tls_verify_host** (boolean) Verify certificate hostname (CN). The default is ``false``.
   * **tls_default_ca** (boolean) Load additional TLS certificates (ca_root_nss). The default is ``false``.
   * **tls_client_cert** (string) Use the following ``pki:X`` as client certificate. The default is to not send a client certificate.
   * **background** (boolean) Perform request in the background. In which case this function returns ``true`` if the queueing was successful, otherwise ``None`` on errors. The default is ``false``.
   * **background_hash** (number) Assign this request to a specific queue. If this value is higher than the number of queues, it's chosen by modulus. The default is queue ``0``.
   * **background_retry_count** (number) Number of retry attempts made after the initial failure. The default is ``0``.
   * **background_retry_delay** (number) The delay, in seconds, before each retry attempt. The default is ``0`` seconds.
   * **proxy** (string) Use a HTTP proxy. See CURL_PROXY manual. The default is to inherit proxy settings from the system.

  If the option ``extended_result`` result is ``true``. This function will return an array containing the ``status`` code and ``content``. If no valid HTTP response is receivied `None` is return.

	.. code-block:: hsl

	  $response = http("http://halon.io/", [
              "extended_result" => true,
              "headers" => ["Host: example.com", "Accept: application/json"]
              ]);
	  if ($response) {
		  echo $response;
	  }

Mail
----

.. function:: header_addresslist_extract(value, [options])

  Extract addresses from a header value or field, often used with `From`, `To` and `CC` headers. On error `None` is returned.

  :param string value: value to extract email addresses from
  :param array options: an options array
  :return: email addresses
  :rtype: array

  The following options are available in the options array.

   * **field** (boolean) If the value is a header field (Header: Value) format. The default is ``false``.

  .. code-block:: hsl

    $fromAddresses = header_addresslist_extract("Charlie <charlie@example.org>; James <james@example.com>");
    if ($fromAddresses and length($fromAddresses) > 1)
      echo "Too many From addresses";

.. function:: header_dkim_decode(value, [options])

  Decode a Tag=Value list from a DKIM header value or field, often used with `DKIM-Signature` or `ARC-` headers. On error `None` is returned.

  :param string value: value to extract tags from
  :param array options: an options array
  :return: tags
  :rtype: array

  The following options are available in the options array.

   * **field** (boolean) If the value is a header field (Header: Value) format. The default is ``false``.

  .. code-block:: hsl

    $tags = header_dkim_decode("v=1; d=domain; s=selector; h=to:from:date:subject");
    if ($tags and isset($tags["s"]) and isset($tags["d"]))
      echo $tags["s"]."._domainkey.".$tags["d"];

.. function:: xtext_encode(text)

  Encode `xtext` according to the `rfc1891 <https://tools.ietf.org/html/rfc1891>`_.

  :param string text: value to encode
  :return: the encoded value
  :rtype: string

.. function:: xtext_decode(text)

  Decode `xtext` according to the `rfc1891 <https://tools.ietf.org/html/rfc1891>`_.

  :param string text: value to decode
  :return: the decoded value
  :rtype: string

.. function:: dnsbl(ip, hostname, [resolvers, [timeout = 5]])

  Query the resolvers for the DNSBL status of an address. If no resolvers are given, the system default is used.

  :param string ip: IP or IPv6 address to check
  :param string hostname: in DNSBL list
  :param array resolvers: list of resolvers
  :param number timeout: timeout in seconds
  :return: list of IP addresses
  :rtype: array

  This function works by reversing the IP addresses octets and appending to the hostname parameter.

.. function:: spf_query(ip, helo, domain, [options])

  Check the SPF status of the senderdomain.

  :param string ip: IP or IPv6 address to check
  :param string helo: HELO/EHLO host name
  :param string domain: domain to lookup
  :param array options: options array
  :return: the result
  :rtype: array

  The following options are available in the options array.

   * **timeout** (number) Query timeout in seconds. The default is ``5``.
   * **servers** (array) List of resolvers. The default is the system wide.

  An array with a ``result`` field as an associative array. The ``result`` is returned as the string result as defined by libspf2 (eg. ``pass``).

  +----------------------+-----------+
  | SPF_RESULT_INVALID   | invalid   |
  +----------------------+-----------+
  | SPF_RESULT_NEUTRAL   | neutral   |
  +----------------------+-----------+
  | SPF_RESULT_PASS      | pass      |
  +----------------------+-----------+
  | SPF_RESULT_FAIL      | fail      |
  +----------------------+-----------+
  | SPF_RESULT_SOFTFAIL  | softfail  |
  +----------------------+-----------+
  | SPF_RESULT_NONE      | none      |
  +----------------------+-----------+
  | SPF_RESULT_TEMPERROR | temperror |
  +----------------------+-----------+
  | SPF_RESULT_PERMERROR | permerror |
  +----------------------+-----------+

.. function:: globalview(ip)

  Query the embedded Cyren IP reputation, ``ctipd``.
  This function is only available in the full system distribution (virtual machine) package.
  All connectors are available in the `script library <https://github.com/halon/hsl-examples/>`_.

  :param string ip: IP or IPv6 address to check
  :return: the recommended action to take for the ip ``accept``, ``tempfail`` or ``permfail``.
  :rtype: string

Mathematical
------------

.. function:: abs(number)

  Return the absolute value of a number.

  :param number number: the numeric value to process
  :return: the absolute value
  :rtype: number

.. function:: ceil(number)

  Return the integer value of a number by rounding up if necessary.

  :param number number: the numeric value to process
  :return: the integer value
  :rtype: number

.. function:: floor(number)

  Return the integer value of a number by rounding down if necessary.

  :param number number: the numeric value to process
  :return: the integer value
  :rtype: number

.. function:: log(number, [base = e])

  Return the logarithm of number to base.

  :param number number: the numeric value to process
  :param number base: the base
  :return: the logarithm value
  :rtype: number

.. function:: pow(base, exponent)

  Return base raised to the power of the exponent.

  :param number base: the base
  :param number exponent: the exponent
  :return: the power of
  :rtype: number

.. seealso::
	It's significantly faster to use the ** operator since it's an operator and not a function.

.. function:: round(number, [decimals = 0])

  Return number rounded to precision of decimals.

  :param number number: the numeric value to process
  :param number decimals: the number of decimals
  :return: the rounded value
  :rtype: number

.. function:: sqrt(number)

  Return the square root of number.

  :param number number: the numeric value to process
  :return: the square root
  :rtype: number

MIME
----

.. class:: MIME

  This is a MIME "string builder" used to construct MIME parts. In the :doc:`end-of-DATA <eod>` context there is a similar :cpp:class:`MIMEPart` object as well (however it has other member functions available), which is used to work with a message's MIME parts.
  
  .. function:: MIME.constructor()

    The MIME object "constructor" takes no function arguments.

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

  .. function:: MIME.addHeader(name, value, [options])

	  Add a header. The value may be encoded (if needed) and reformatted.

	  :param string name: name of the header
	  :param string value: value of the header
	  :param array options: an options array
	  :return: this
	  :rtype: :class:`MIME`

	  The following options are available in the options array.

	   * **encode** (boolean) Refold and encode the header. The default is ``true``.

	  .. note::

		If a `Content-Type` header is added, the value of :func:`MIME.setType` is ignored. If a `Content-Transfer-Encoding` header is added no encoding will be done on data added by :func:`MIME.setBody`.

  .. function:: MIME.appendPart(part)

	  Add a MIME part (child) object, this is useful when building a multipart MIME.

	  :param MIME part: a MIME part object
	  :return: this
	  :rtype: :class:`MIME`

	  .. note::

		The `Content-Type` is not automatically set to `multipart/\*`, this has to be done using :func:`MIME.setType`. The MIME boundary is however automatically created.

  .. function:: MIME.setBody(body)

	  Set the MIME part body content. In case the MIME part has children (multipart) this will be the MIME parts preamble. The body will be Base64 encoded if no `Content-Transfer-Encoding` header is added.

	  :param string body: the body
	  :return: this
	  :rtype: :class:`MIME`

  .. function:: MIME.setType(type)

	  Set the type field of the `Content-Type` header. The default type is `text/plain`, and the charset is always utf-8.

	  :param string type: the content type
	  :return: this
	  :rtype: :class:`MIME`

  .. function:: MIME.setBoundary(boundary)

	  Set the MIME boundary for `multipart/\*` messages. The default is to use an UUID.

	  :param string boundary: the boundary
	  :return: this
	  :rtype: :class:`MIME`

  .. function:: MIME.signDKIM(selector, domain, key, [options])

	  Sign the MIME structure (message) using `DKIM <https://docs.halon.io/go/dkim>`_.

	  :param string selector: selector to use when signing
	  :param string domain: domain to use when signing
	  :param string key: private key to use, either ``pki:X`` or a private RSA key in PEM format.
	  :param array options: options array
	  :return: this
	  :rtype: :class:`MIME`

	  The following options are available in the options array.

	   * **canonicalization_header** (string) body canonicalization (``simple`` or ``relaxed``). The default is ``relaxed``.
	   * **canonicalization_body** (string) body canonicalization (``simple`` or ``relaxed``). The default is ``relaxed``.
	   * **algorithm** (string) algorithm to hash the message with (``rsa-sha1``, ``rsa-sha256`` or ``ed25519-sha256``). The default is ``rsa-sha256``.
	   * **additional_headers** (array) additional headers to sign in addition to those recommended by the RFC.
	   * **oversign_headers** (array) headers to oversign. The default is ``from``.
	   * **headers** (array) headers to sign. The default is to sign all headers recommended by the RFC.
	   * **id** (boolean) If the key is expected to be in the ``pki:X`` format. The default is auto detect.

  .. function:: MIME.toString()

	  Return the created MIME as a string. This function useful for debugging.

	  :return: the MIME as string
	  :rtype: string

  .. function:: MIME.queue(sender, recipient, transportid, [options])

	  Put the MIME message (email) into the queue.

	  :param sender: the sender email address, either as a string or an associative array with a ``localpart`` and ``domain``
	  :type sender: string or array
	  :param recipient: the recipient email address, either as a string or an associative array with a ``localpart`` and ``domain``
	  :type recipient: string or array
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
			->queue("", ["localpart" => "info", "domain" => "example.com"], "mailtransport:1");

Misc
----

.. function:: serial()

  The serial number of the installation. It can be used to identify a software instance.
  This function is only available in the full system distribution (virtual machine) package.

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

  Collect statistics based on one or more legend (value).
  This function is only available in the full system distribution (virtual machine) package.
  Connectors for external time-series databases such as Graphite or InfluxDB
  are available in the `script library <https://github.com/halon/hsl-examples/>`_.

  The `name` is the name of the graph (the collection of `legends`). A legend is a value for which the system should collect statistics.

  :param string name: name of the graph
  :param array legends: key value pair of legends
  :rtype: none

  Values stat'ed are available

   * as a line graph (on the graphs and report page)
   * as a pie chart (on the graphs and report page)
   * using the REST API.
   * using SNMP

  In order for the line graph to work properly, all values should be defined to the stat function on every `stat` call (even if they are not increased).

  .. code-block:: hsl

	  $fam4 = 0; $fam6 = 0;
	  if (inet_includes($senderip, "0.0.0.0/0")) { $fam4 = 1; } else { $fam6 = 1; }
	  stat("ip-family", ["ipv4" => $fam4, "ipv6" => $fam6]);

  .. note::

	You can only use "a-z0-9.-" in the name and "a-zA-Z0-9-_" in the legends (legends longer than 19 characters will be truncated on the graph page) when using the stat function.

.. function:: inet_includes(ip, network)

  Returns true if `ip` is in the subnet or range of `network`. Both IPv4 and IPv6 are supported.

  :param string ip: ip address
  :param string network: address, subnet or range.
  :return: true if ip is in network
  :rtype: boolean

  .. code-block:: hsl

	inet_includes("127.0.0.1", "127.0.0.1/8");
	inet_includes("127.0.0.1", "127.0.0.0-127.255.255.255");
	inet_includes("127.0.0.1", "127.0.0.1");
	inet_includes("2001:4860:4860::8888", "2001:4860:4860::/48");

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

.. function:: inet_reverse(ip, [zone])

	Converts an IP to a reverse DNS compatible format (to be used with PTR lookups or DNSxL lookups). By default the zone correspons to the ARPA address for each IP family. On error `None` is returned.

  :param string ip: the ip in printable format
  :param string zone: the zone to append
  :return: an reverse DNS hostname
  :rtype: string

  .. code-block:: hsl

	echo inet_reverse("8.8.8.8"); // 8.8.8.8.in-addr.arpa
	echo inet_reverse("12.34.56.78", "example.com"); // 78.56.34.12.example.com

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

   * **sync** (boolean) Synchronize the rate in between nodes in the cluster. The default is ``true``.

  .. code-block:: hsl

	  if (rate("outbound", $saslusername, 3, 60) == false) {
	        Reject("User is only allowed to send 3 messages per minute");
	  }

  .. note::

  	Rates are shared between all contexts, and may also be synchronized in clusters.

.. function:: mail(sender, recipient, subject, body, transportid, [options])

  Put an email message into the queue.

  :param sender: the sender email address, either as a string or an associative array with a ``localpart`` and ``domain``
  :type sender: string or array
  :param recipient: the recipient email address, either as a string or an associative array with a ``localpart`` and ``domain``
  :type recipient: string or array
  :param string subject: the subject
  :param string body: the body
  :param string transportid: the transport ID
  :param array options: options array
  :return: the queued message ID
  :rtype: string

  The following options are available in the options array.

   * **sender_name** (string) Friendly name of the sender.
   * **recipient_name** (string) Friendly name of the recipient.
   * **headers** (array) Add additional message headers (KVP).
   * **metadata** (array) Add additional metadata to the message (KVP).

  .. code-block:: hsl

	  mail(
			"postmaster@example.com",
			"support@halon.se",
			"Lunch",
			"How about lunch on Friday?",
			"mailtransport:1"
		);

  .. note::

	If you want to build more complex emails use the :class:`MIME` class.

Protocols
---------

.. function:: smtp_lookup_rcpt(server, sender, recipient, [options])

  Check if sender is allowed to send mail to recipient.

  :param server: array with server settings or transport profile ID
  :type server: string or array
  :param sender: the sender (MAIL FROM), either as a string or an associative array with a ``localpart`` and ``domain``
  :type sender: string or array
  :param recipient: the recipient (RCPT TO), either as a string or an associative array with a ``localpart`` and ``domain``
  :type recipient: string or array
  :param array options: options array
  :return: ``1`` if the command succeeded, ``0`` if the command failed and ``-1`` if an error occurred. The ``extended_result`` option may change this behavior.
  :rtype: number or array

  .. include:: func_serverarray.rst

  The following options are available in the options array.

   * **extended_result** (boolean) If ``true`` an associative array with ``error_code``, ``error_message``, ``on_rcptto`` and ``tls`` is returned. The default is ``false``.

.. function:: smtp_lookup_auth(server, username, password)

  Try to authenticate the username against a SMTP server.

  :param server: array with server settings or transport profile ID
  :type server: string or array
  :param string username: username
  :param string password: password
  :return: ``1`` if the authentication succeeded, ``0`` if the authentication failed and ``-1`` if an error occurred.
  :rtype: number

  .. include:: func_serverarray.rst

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

.. class:: LDAP

  The LDAP class is a OpenLDAP wrapper class. The URI should be in the format of ldap:// or ldaps://. Multiple hosts may be given separated by space.

  .. function:: LDAP.constructor(uri)

    :param string uri: The LDAP 
  
    .. code-block:: hsl
  
      $ldap = LDAP("ldap://ldap.forumsys.com");
      $ldap->bind("uid=tesla,dc=example,dc=com", "password");
      $x = $ldap->search("dc=example,dc=com");
      while ($x and $entry = $x->next())
          echo $entry;

  .. function:: LDAP.setoption(name, value)

    Set LDAP connection options.

    :param string name: the option name
    :param number value: the option value
    :return: this
    :rtype: LDAP or None

    .. code-block:: hsl

      if (!$ldap->setoption("network_timeout", 5))
          echo LDAP::err2string($ldap->errno());

    The following options is available

    +------------------+---------+---------+-------------------------------------------------+
    | Name             | Type    | Default | Description                                     |
    +==================+=========+=========+=================================================+
    | protocol_version | number  | 3       |                                                 |
    +------------------+---------+---------+-------------------------------------------------+
    | referrals        | boolean | false   |                                                 |
    +------------------+---------+---------+-------------------------------------------------+
    | network_timeout  | number  | 0       | No timeout                                      |
    +------------------+---------+---------+-------------------------------------------------+
    | timeout          | number  | 0       | No timeout (in seconds)                         |
    +------------------+---------+---------+-------------------------------------------------+
    | timelimit        | number  | 0       | No timelimit (in seconds)                       |
    +------------------+---------+---------+-------------------------------------------------+
    | tls_verify_peer  | boolean | true    | Verify peer certificate                         |
    +------------------+---------+---------+-------------------------------------------------+
    | tls_default_ca   | boolean | false   | Load additional TLS certificates (ca_root_nss)  |
    +------------------+---------+---------+-------------------------------------------------+

  .. function:: LDAP.starttls()

	  Issue STARTTLS on LDAP connection.

	  :return: this
	  :rtype: LDAP or None

  .. function:: LDAP.bind([dn, [cred]])

	  Bind the LDAP connection. For anonymous bind, do not specify the credentials.

	  :param string dn: The username DN
	  :param string cred: The password credentials
	  :return: this
	  :rtype: LDAP or None

  .. function:: LDAP.search(basedn, [options])

    Search LDAP connection in the current base and subtree.

    :param string basedn: Base DN
    :param array options: an options array
    :return: A LDAP result class
    :rtype: :class:`LDAPResult` or None

    The following options are available in the options array.

    * **scope** (string) The search scope, available scopes are ``sub`` (subtree), ``one`` (onelevel) and ``base``. The default is ``sub``.
    * **filter** (string) The search filter. The default is ``(objectclass=*)``.
    * **attributes** (array) Array of attributes to fetch. The default is to fetch all.

  .. function:: LDAP.unbind()

	  Unbind the LDAP connection.

	  :return: this
	  :rtype: LDAP or None

  .. function:: LDAP.errno()

	  Get the latest errno returned from the underlying OpenLDAP API.

	  :return: errno
	  :rtype: number

  .. function:: LDAP.getpeerx509()

	  Get the peer certificate (X.509) as a :class:`X509` instance.

	  :return: The peer certificate
	  :rtype: :class:`X509`

  .. staticmethod:: err2string(errno)

	  Get a descriptive error message, uses OpenLDAP's `ldap_err2string()`.

	  :param number errno: A errno (obtained from LDAP's errno())
	  :return: An error string
	  :rtype: String

	  .. code-block:: hsl

		  if (!$ldap->bind())
		      echo LDAP::err2string($ldap->errno());

  .. staticmethod:: filter_escape(value)

	  LDAP escape values to be used in LDAP filters.

	  :param string value: An unescaped string
	  :return: An escaped string
	  :rtype: String

	  .. code-block:: hsl

		  $result = $ldap->search("dc=example,dc=com", ["filter" => "(cn=" . LDAP::filter_escape($cn) . ")"]);

  .. staticmethod:: str2dn(str)

    Parses the string representation of a distinguished name `str` into its components, returning an array of tupels.

    :param string value: String representation of a DN
    :return: Array of tupels
    :rtype: Array

    .. code-block:: hsl

      echo LDAP::str2dn("cn=admin,dc=example,dc=org");
      // [0=>[0=>"cn",1=>"admin"],1=>[0=>"dc",1=>"example"],2=>[0=>"dc",1=>"org"]]

  .. staticmethod:: dn2str(dn)

    Performs the inverse operation of :func:`LDAP.str2dn`, returning a string representation of `dn` with the necessary escaping.

    :param array value: Array of tupels
    :return: String representation of the DN
    :rtype: String

.. class:: LDAPResult

  A LDAP result iterable object which holds the result from an LDAP search.

  .. function:: LDAPResult.next()

    Return the next result.

    :return: entry data
    :rtype: array or None

    .. code-block:: hsl

      $result = $ldap->search("dc=example,dc=com");
      if ($result)
        while ($entry = $result->next())
          echo $entry;

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

.. function:: str_repeat(string, multiplier)

  Returns the string repeated multiplier times.

  :param string string: the input string
  :param number multiplier: the string multiplier
  :return: the repeated string
  :rtype: string

  .. seealso::
	  It's significantly faster to use the string repeat * operator since it's an operator and not a function.

.. function:: str_replace(search, replace, subject)

  Returns the string subject with the string search replace with replace.

  :param string search: the search string
  :param string replace: the replace string
  :param string subject: the string acted upon
  :return: subject with searched replaced with replace
  :rtype: string

.. function:: str_split(string, delimiter, [limit = 0])

  Splits the string into an array on the delimiter.

  :param string string: the string
  :param string delimiter: the delimiter
  :param number limit: the maximum number of parts returned
  :return: an array of strings
  :rtype: array

  .. code-block:: hsl

	str_split("how are you", " ",  2) // ["how","are you"]
	str_split("how are you", " ", -2) // ["how are","you"]

  .. seealso::
	  To join an array to a string, see :func:`array_join`.

.. function:: str_find(string, substring, [offset = 0])

  Return the position (starting from zero) of the first occurrence of substring in the string (starting from the offset). If the substring is **not** found -1 is returned.

  :param string string: the input string
  :param string substring: the string to look for
  :param number offset: the offset from the start
  :return: the position where substring is found
  :rtype: number

.. function:: str_rfind(string, find, [offset = 0])

  Return the position (starting from zero) of the last occurrence of substring in the string searching backward (starting from the offset relative to the end). If the substring is **not** found -1 is returned.

  :param string string: the input string
  :param string substring: the string to look for
  :param number offset: the offset from the end
  :return: the position where substring is found
  :rtype: number

.. function:: str_lower(string)

  Returns string with all US-ASCII character to lowercased.

  :param string string: the input string
  :return: the string lowercased
  :rtype: string

.. function:: str_upper(string)

  Returns string with all US-ASCII character uppercased.

  :param string string: the input string
  :return: the string uppercased
  :rtype: string

.. function:: str_slice(string, offset, [length])

  Return the substring of string.

  :param string string: the input string
  :param number offset: the start position
  :param number length: the length limit if given
  :return: the substring
  :rtype: string

  .. seealso::
	  It's significantly faster to use the slice [:] operator since it's an operator and not a function.

.. function:: str_strip(string)

  Returns string with whitespace characters (`\\s\\t\\r\\n`) removed from the start and end of the string.

  :param string string: the input string
  :return: the trimmed string
  :rtype: string

Regular expression
------------------

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

.. class:: Socket

  This class allows POSIX like socket(2) code. A socket resource is created for each Socket instance, this resource is automatically garbage collected (closed) once the object is destroyed.

  .. function:: Socket.constructor(family, type)

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

  .. function:: Socket.recv(length, [flags])

	  Receive data on socket.

	  :param number length: up to length bytes to receive
	  :param string flags: flags to control the behaviour
	  :return: data
	  :rtype: string or None

	  Flags may be any of, the default is no POSIX recv(3) flag.

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

.. class:: TLSSocket

  This class allows OpenSSL like SSL(3) code. The TLSSocket class takes a connected :class:`Socket` instance (SOCK_STREAM) and encapsulates any read and writes in TLS/SSL.

  .. function:: TLSSocket.constructor(socket, options)

    :param Socket socket: a socket
    :param array options: options array

    The following options are available in the options array.

     * **tls_protocols** (string) Use one or many of the following TLS protocols; ``SSLv2``, ``SSLv3``, ``TLSv1``, ``TLSv1.1``, ``TLSv1.2`` or ``TLSv1.3``. Protocols may be separated by ``,`` and excluded by ``!``. The default is ``!SSLv2,!SSLv3``.
     * **tls_ciphers** (string) List of ciphers to support. The default is decided by OpenSSL for each ``tls_protocol``.
     * **tls_verify_name** (array) Hostnames to verify against the certificate's CN and SAN (NO_PARTIAL_WILDCARDS | SINGLE_LABEL_SUBDOMAINS).
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

  .. function:: TLSSocket.recv(length)

	  Receive data on TLS/SSL socket. This function may perform an implicit handshake.

	  :param number length: up to length bytes to recv
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

  .. function:: TLSSocket.getpeerx509()

	  Get the peer certificate (X.509) as a :class:`X509` instance.

	  :return: The peer certificate
	  :rtype: :class:`X509`

.. class:: X509

  This class allows you to parse an X509 resource. The X509 class takes a `X509Resource`.

  .. function:: X509.constructor(x509resource)

    :param X509Resource x509resource: a X509Resource

  .. function:: X509.subject()

    The subject of the certificate. The first field in the tuple is the name (eg. CN, OU) and the second is the value.

    :return: The subject
    :rtype: array of [string, string]

  .. function:: X509.issuer()

    The issuer of the certificate. The first field in the tuple is the name (eg. CN, OU) and the second is the value.

    :return: The issuer
    :rtype: array of [string, string]

  .. function:: X509.subject_alt_name()

    The subject alt names (DNS) items

    :return: The SAN
    :rtype: array

  .. function:: X509.version()

    The version of the X.509 certificate

    :return: The version
    :rtype: number

  .. function:: X509.serial_number()

    The serial number in HEX

    :return: The serial
    :rtype: string

  .. function:: X509.not_valid_before()

    The start date of the certificate (in unix time)

    :return: The certificate start date
    :rtype: number

  .. function:: X509.not_valid_after()

    The end date of the certificate (in unix time)

    :return: The certificate end date
    :rtype: number

  .. function:: X509.public_key([options])

    Export the public key in binary DER format (default) or in PEM format.

    :param array options: options array
    :return: The public key
    :rtype: string

    The following options are available in the options array.

     * **pem** (boolean) Export the public key in PEM format. The default is ``false``.

  .. function:: X509.export([options])

    Export the certificate in binary DER format (default) or in PEM format.

    :param array options: options array
    :return: The certificate
    :rtype: string

    The following options are available in the options array.

     * **pem** (boolean) Export the X.509 in PEM format. The default is ``false``.

    .. code-block:: hsl

        // SHA256 fingerprint
        echo sha2($c->export(), 256);

FFI
---

The foreign function interface (FFI) enables loading of shared libraries following C interface calling conventions. The FFI interface has its own types (C types) and memory. It's very easy to crash the Halon script engine if not used properly.

.. class:: FFI

  This class allows you to load a shared object/library.

  .. function:: FFI.constructor(path)

    :param string path: a library (eg. libc.so.7)

    .. code-block:: hsl

     $libc = FFI("libc.so.7");

  .. function:: FFI.func(name, arguments, returntype)

    The name of the function to load, use :func:`FFI.type` to define the correct function signature. If the function is not found, None is returned.

    :param string name: the function name
    :param FFIType arguments: the list of argument types
    :param FFIType returntype: the return type
    :return: A function object
    :rtype: :data:`FFIFunction`

    .. code-block:: hsl

      $malloc = $libc->func("malloc", [ FFI::type("uint64") ], FFI::type("pointer"));
      $free = $libc->func("free", [ FFI::type("pointer") ], FFI::type("void"));
      $printf = $libc->func("printf", [ FFI::type("pointer"), FFI::type("...") ], FFI::type("sint64")); // variadic

    The ``...`` type prepresents functions having a variadic arguments list. All values in the variadic arguments list must have an explicit type since they are unknown in the function definition.

  .. function:: FFI.symbol(name)

    Return a pointer to a global symbol in the library (eg. a variable). This function is the equivalent of dlsym(2). If the symbol is not found, None is returned.

    :param string name: a symbol name
    :return: An FFIValue of pointer type
    :rtype: :data:`FFIValue`

  .. staticmethod:: type(name)

    A factory function for FFI types. This function is usually used to declare the function signature of an :data:`FFIFunction`.

    :param string name: a type name
    :return: An FFI type
    :rtype: :data:`FFIType`

    The following types are available.

    * ``void`` (can only be used as return value)
    * ``uint8``, ``sint8``, ``uint16``, ``sint16``, ``uint32``, ``sint32``, ``uint64``, ``sint64``
    * ``float``, ``double``
    * ``pointer``
    * ``...`` (can only be used as function argument)

  .. staticmethod:: cnumber(type, number)

    Create an :data:`FFIValue` containing a C number. It's a basic type, which exists for the lifetime of the returned value and passed by value.
  
    :param FFIType type: an FFI C number type
    :param number number: a number
    :return: An FFI value
    :rtype: :data:`FFIValue`

    The following FFI number types are available:

    * ``uint8``, ``sint8``, ``uint16``, ``sint16``, ``uint32``, ``sint32``, ``uint64``, ``sint64``, ``float``, ``double``

    .. code-block:: hsl

      $malloc = $libc->func("malloc", [ FFI::type("uint64") ], FFI::type("pointer"));
      $ptr = $malloc(FFI::cnumber(FFI::type("uint64"), 32));

  .. staticmethod:: cstring(value)

    Allocate a null-terminated C string (``char *``) in memory from a HSL string and return an :data:`FFIValue` of ``pointer`` type pointing to that memory. This memory is owned by the :data:`FFIValue` resource (use the :func:`FFI.detach` function to disclaim ownership). This function is intentionally not binary safe.

    :param string value: a string
    :return: An FFIValue of pointer type
    :rtype: :data:`FFIValue`

    .. code-block:: hsl

      $fopen = $libc->func("fopen", [ FFI::type("pointer"), FFI::type("pointer") ], FFI::type("pointer"));
      $fp = $fopen(FFI::cstring("/dev/zero"), FFI::cstring("r"));

  .. staticmethod:: nullptr()

    Create an :data:`FFIValue` containing a NULL pointer.

    :return: An FFIValue of pointer type
    :rtype: :data:`FFIValue`

    .. note::
      
      The C equivalent of this function is ``NULL``.
  
  .. staticmethod:: allocate(size)

    Allocate memory of `size` in bytes and return an :data:`FFIValue` of ``pointer`` type pointing to that memory. This memory is owned by the :data:`FFIValue` resource (use the :func:`FFI.detach` function to disclaim ownership). The memory is initially filled with zeros.

    :param any size: the memory size in bytes
    :return: An FFIValue of pointer type
    :rtype: :data:`FFIValue`

    .. note::
      
      The C equivalent of this function is ``malloc(size)`` with a ``memset(pointer, size, 0)``.

  .. staticmethod:: memcpy(pointer, data)

    Copy the binary content of (string) data into memory location pointed to by an :data:`FFIValue` of ``pointer`` type. The caller must make sure the pointer location is of sufficient length.
  
    :param FFIValue pointer: an FFIValue of pointer type
    :param string data: the data to copied
    :return: An FFIValue
    :rtype: :data:`FFIValue`
  
    .. note::
      
      The C equivalent of this function is ``memcpy(pointer, data, datalen)``.

  .. staticmethod:: byref(value)

    Return an :data:`FFIValue` of ``pointer`` type pointing to the :data:`FFIValue` `value`.

    :param FFIValue value: an FFI value
    :return: An FFIValue of pointer type
    :rtype: :data:`FFIValue`

    .. note::

      The C equivalent of this function is ``&value``.

  .. staticmethod:: deref(value, [type])

    Return an :data:`FFIValue` with :data:`FFIType` of `type` with the value at the address pointed at by the :data:`FFIValue` value. The default type is ``pointer``. If the type is a pointer and dereferenced pointer points to NULL then None is returned.

    :param FFIValue value: an FFI value
    :param FFIType type: an FFI type
    :return: An FFIValue of pointer type
    :rtype: :data:`FFIValue`

    .. note::

      The C equivalent of this function is ``*value``.

  .. staticmethod:: offset(pointer, offset)

    Return a new :data:`FFIValue` of ``pointer`` type pointing the same memory with an offset.

    :param FFIValue pointer: an FFI value of pointer type
    :param number offset: the offset in bytes
    :return: An FFIValue of pointer type
    :rtype: :data:`FFIValue`

    .. note::
      
      The C equivalent of this function is ``pointer + 32``.

  .. staticmethod:: string(pointer, [size])

    Copy the binary content of a memory location pointed to by an :data:`FFIValue` of ``pointer`` type to a HSL string. If the size is omitted the memory will be copied up to the first NULL character as a null-terminated C string (``char *``).
  
    :param FFIValue pointer: an FFI value of pointer type
    :param number size: bytes to copy
    :return: A binary safe string
    :rtype: string

  .. staticmethod:: number(value)

    Convert an FFI value to a HSL number. The number type can safely represent all integers between `+/-9007199254740991` (the equivalent of ``(2 ** 53) - 1``). If you expect to work with greater numbers use :func:`FFI.number64`.

    :param FFIValue value: an FFI value
    :return: A number
    :rtype: number

  .. staticmethod:: number64(value)

    Convert an FFI value (``uint64``, ``sint64`` or ``pointer``) to a pair of two 32 bit integers ([high, low]). For signed negative numbers a two complement representation is used.

    :param FFIValue value: an FFI value
    :return: A number pair
    :rtype: array of number

  .. staticmethod:: attach(pointer, [destructor])

    Assign the ownership of the data pointed to by the pointer argument (:data:`FFIValue` of ``pointer`` type). The default destructor is `free`. An optional destructor :data:`FFIFunction` (should have one ``pointer`` argument) may be given.
  
    :param FFIValue pointer: an FFIValue of pointer type
    :param FFIFunction destructor: a destructor function
    :return: The pointer argument
    :rtype: :data:`FFIValue`

    .. code-block:: hsl

      $fopen = $libc->func("fopen", [ FFI::type("pointer"), FFI::type("pointer") ], FFI::type("pointer"));
      $fclose = $libc->func("fclose", [ FFI::type("pointer") ], FFI::type("void"));
      $fp = FFI::attach($fopen->call("/dev/zero", "r"), $fclose);

  .. staticmethod:: detach(pointer)

    Remove the ownership of the data pointed to by the pointer argument (:data:`FFIValue` of ``pointer`` type).
  
    :param FFIValue pointer: an FFI value of pointer type
    :return: The pointer argument
    :rtype: :data:`FFIValue`

.. function:: FFIFunction(...args)

    A callable Function object of type FFIFunction.

    :param FFIValue args: FFIValues or a HSL value
    :return: Return value of function call 
    :rtype: :data:`FFIValue` or None (for ``void`` or a ``pointer`` returning `NULL`)

    Implicit conversion can be made if the function signature has once of the following types and the argument HSL type match. Note that the lifetime of converted values are for the duration of the function call. 

	  +------------------+----------+------------------------------------+
	  | Declaration type | HSL type | Conversion                         |
	  +==================+==========+====================================+
	  | ``pointer``      | String   | :func:`FFI.cstring`                |
	  +------------------+----------+------------------------------------+
	  | ``pointer``      | None     | :func:`FFI.nullptr`                |
	  +------------------+----------+------------------------------------+
	  | `any number`     | Number   | Be causes of value truncations     |
	  +------------------+----------+------------------------------------+
	  | ``...``          | FFIValue | Argument must be of explicit type  |
	  +------------------+----------+------------------------------------+

    .. code-block:: hsl

      $fopen = $libc->func("fopen", [ FFI::type("pointer"), FFI::type("pointer") ], FFI::type("pointer"));
      $fp1 = $fopen(FFI::cstring("/dev/zero"), FFI::cstring("r"));
      $fp2 = $fopen("/dev/zero", "r"); // implicit conversion
      $printf("%s %zu\n", FFI::cstring("hello"), FFI::cnumber(FFI::type("uint64"), 123));

.. data:: FFIType

  An FFIType resource holds information about a function signature.

  The following types are available.

    * ``void`` (can only be used as return value)
    * ``uint8``, ``sint8``, ``uint16``, ``sint16``, ``uint32``, ``sint32``, ``uint64``, ``sint64``
    * ``float``, ``double``
    * ``pointer``
    * ``...`` (can only be used as function argument)

.. data:: FFIValue

  An FFIValue resource is a container for an FFI value (it also contains the FFIType) so that the correct conversions can be made. If the value is of a pointer type you may control the lifetime of the object using the :func:`FFI.attach` and :func:`FFI.detach` functions.
