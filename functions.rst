Standard library
================

Functions which are documented in this chapter are considered `core` functions hence are available in all `contexts`. Functions in the standard library may be recognized by the fact that they are all in lowercase.

* **Array** :func:`array_keys` :func:`array_reverse` :func:`count` :func:`explode` :func:`implode` :func:`in_array` :func:`range`
* **Cryptographic** :func:`hmac_md5` :func:`hmac_sha1` :func:`md5` :func:`sha1`
* **Data types** :func:`array` :func:`number` :func:`string` :func:`is_array` :func:`is_number` :func:`is_string` :func:`isset` :func:`unset`
* **Date and time** :func:`executiontime` :func:`rand` :func:`sleep` :func:`strftime` :func:`time` :func:`timelocal` :func:`uptime`
* **DNS** :func:`dns` :func:`dns4` :func:`dns6` :func:`dnsmx` :func:`dnsptr` :func:`dnstxt` :func:`is_subdomain`
* **Encodings and JSON** :func:`base64_encode` :func:`base64_decode` :func:`json_encode` :func:`json_decode` :func:`hash`
* **File and HTTP** :func:`file` :func:`file_get_contents` :func:`in_file` :func:`http`
* **Mail** :func:`smtp_lookup_rcpt` :func:`smtp_lookup_auth` :func:`dovecot_lookup_auth` :func:`ldap_search` :func:`ldap_bind` :func:`radius_authen` :func:`tacplus_authen` :func:`tacplus_author` :func:`dnsbl` :func:`spf` :func:`globalview` :func:`mail`
* **Mathematical** :func:`abs` :func:`ceil` :func:`floor` :func:`log` :func:`pow` :func:`round` :func:`sqrt`
* **Misc** :func:`serial` :func:`gethostname` :func:`uuid` :func:`syslog` :func:`stat` :func:`in_network` :func:`rate`
* **String** :func:`chr` :func:`str_repeat` :func:`str_replace` :func:`strlen` :func:`strpos` :func:`strrpos` :func:`strtolower` :func:`strtoupper` :func:`substr` :func:`trim` :func:`pcre_match` :func:`pcre_match_all` :func:`pcre_quote` :func:`pcre_replace`

Array
-----

.. function:: array_keys(array)

  Returns the keys in the array.

  :param array array: the array
  :return: array's keys
  :rtype: array

.. function:: array_reverse(array)

  Return array in reverse order

  :param array array: the array
  :return: array in reverse order
  :rtype: array

.. function:: count(array)

  Counts items in an array.

  :param array array: the array
  :return: the number of items in array
  :rtype: number

.. function:: explode(delimiter, string)

  Splits the string into an array on the delimiter.

  :param string delimiter: the delimiter
  :param string string: the string
  :return: an array of strings
  :rtype: array

.. function:: implode(glue, array)

  Joins the array with the glue.

  :param string glue: the glue
  :param array array: the array
  :return: a string from an array
  :rtype: string

.. function:: in_array(value, array)

  Returns true if value is found in the array.

  :param any value: the value to search for
  :param array array: the array
  :return: true if value is found
  :rtype: boolean

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

Data types
----------

.. function:: array([...])

  This function creates an array.

  :param any any: the input
  :return: an array
  :rtype: array

  .. note::

	`array` is not a function, it's a language contrustruct to create an :ref:`array <arraytype>` type. It's an alias for the short array syntax ``[]``.

.. function:: number(x)

  This function fast the input of x to the number type.

  :param any x: the input
  :return: a number
  :rtype: number

.. function:: string(x)

  This function fast the input of s to the string type, hence converting it to it's string representation.

  :param any x: the input
  :return: a string
  :rtype: string

.. function:: is_array(a)

  Returns true if the value of a is an array.

  :param any a: the input
  :return: the result
  :rtype: boolean

.. function:: is_number(n)

  Returns true if the value of n is a number.

  :param any n: the input
  :return: the result
  :rtype: boolean

.. function:: is_string(s)

  Returns true if the value of s is a string.

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

  Unsets the variable or array index of x, it return true if the variable or array index was defined.

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

.. function:: strftime(format)

  Format according to the `strftime <http://www.freebsd.org/cgi/man.cgi?query=strftime>`_ manual with the time without timezone.

  .. code-block:: hsl

	 echo strftime("%H:%M:%S"); // prints current time eg "13:58:38"

  :param string format: the format string
  :return: the time formated (max length 100)
  :rtype: string

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

.. function:: dns(hostname, [resolvers, [timeout = 5]])

  Query the resolvers for the A and AAAA record of the hostname. If no resolvers are given, the system default is used.

  :param string hostname: the hostname
  :param array resolvers: list of resolvers
  :param number timeout: timeout in seconds
  :return: list of IP addresses
  :rtype: array

.. function:: dns4(hostname, [resolvers, [timeout = 5]])

  Query the resolvers for the A record of the hostname. If no resolvers are given, the system default is used.

  :param string hostname: the hostname
  :param array resolvers: list of resolvers
  :param number timeout: timeout in seconds
  :return: list of IP addresses
  :rtype: array

.. function:: dns6(hostname, [resolvers, [timeout = 5]])

  Query the resolvers for the AAAA record of the hostname. If no resolvers are given, the system default is used.

  :param string hostname: the hostname
  :param array resolvers: list of resolvers
  :param number timeout: timeout in seconds
  :return: list of IPv6 addresses
  :rtype: array

.. function:: dnsmx(hostname, [resolvers, [timeout = 5]])

  Query the resolvers for the MX record of the hostname. If no resolvers are given, the system default is used.

  :param string hostname: the hostname
  :param array resolvers: list of resolvers
  :param number timeout: timeout in seconds
  :return: list of MX records
  :rtype: array

.. function:: dnsptr(address, [resolvers, [timeout = 5]])

  Query the resolvers for the PTR record of the address. If no resolvers are given, the system default is used.

  :param string address: the address (IPv4 or IPv6)
  :param array resolvers: list of resolvers
  :param number timeout: timeout in seconds
  :return: the PTR record of address
  :rtype: array

.. function:: dnstxt(hostname, [resolvers, [timeout = 5]])

  Query the resolvers for the TXT record of the hostname. If no resolvers are given, the system default is used.

  :param string hostname: the hostname
  :param array resolvers: list of resolvers
  :param number timeout: timeout in seconds
  :return: list of TXT records
  :rtype: array

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

.. function:: json_encode(value, [options])

  JSON encode a HSL data type.

  :param any value: HSL data type
  :param array options: options array
  :return: a JSON representation of value
  :rtype: string

  The following options are available in the options array.

   * **ensure_ascii** (boolean) Convert all non-ASCII characters (UTF-8) to unicode (`\\uXXXX`). The default is ``true``.

  Encode an array, number or string into a JSON representation (string). The encoding distinguishes arrays from objects if they are sequentially numbered from zero. On encoding errors an object with the data type of undefined is returned. All non-ASCII characters will be escaped as Unicode code points (\\uXXXX).

  .. note::

	  Since object keys are converted to strings (even numeric once) a :func:`json_encode` followed by a :func:`json_decode` does not always yield the same result.

.. function:: json_decode(string)

  Decodes a JSON string into a HSL data type.

  :param string string: JSON serialized data
  :return: any HSL data type on errors ``none`` is returned.
  :rtype: any

  The following translations are done (JSON to HSL).

  * **object** to **associative array** (is_array)
  * **array** to **array** (is_array)
  * **string** to **string** (is_string)
  * **number** to **number** (is_number)
  * **true** to ``1`` (is_number)
  * **false** to ``0`` (is_number)
  * **null** to **none** (check for expected type instead)

.. function:: hash(string)

  Return the numeric hash value of the input string. The hash value is same for equal strings.

  :param string string: string to be hased
  :return: a hash value
  :rtype: number

File and HTTP
-------------
The filename may point to a file in the configuration ``file:X`` or a file relative on the accessible filesystem ``file://filename.txt``.

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

.. function:: in_file(word, filename, [options])

  Searches for word at the beginning (`index`) of each line in filename. If found, the line is returned as an array separated by the `delimiter`.

  :param string word: the string to look for
  :param string filename: the file name
  :param array options: options array
  :return: if word is found in string, return all words on that line as an array
  :rtype: array

  The following options are available in the options array.

   * **type** (string) may be ``text/plain`` or ``text/csv``. In `text/csv` mode the delimiter is changed to ``,`` and the first line may be used as ``index``. The default type is ``text/plain``.
   * **delimiter** (string) separates words. The default is a white space for `text/plain` and ``,`` for `text/csv`.
   * **assoc** (boolean) in `text/csv` mode the first line may be used as associative index for the returned array. The default is ``true``.
   * **index** (number) the word index to search for (indexed at zero). The default is ``0`` (the first word).

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

.. function:: http(url, [options, [get, [post]]]])

  Make HTTP/HTTPS request to a URL and return the content

  :param string url: URL to request
  :param array options: options array
  :param array get: GET variables, replaced and encoded in URL as $1, $2...
  :param post: POST data as an array or a string for raw POST data
  :type post: array or string
  :return: if the request was successful (2XX) the content is returned
  :rtype: string

  The following options are available in the options array.

   * **connect_timeout** (numbers) Connection timeout (in seconds). The default is ``10`` seconds.
   * **timeout** (number) Timeout (in seconds) waiting for data once the connection is established. The default is to wait indefinitely.
   * **method** (string) Request method. The default is ``GET`` unless ``POST`` data is sent.
   * **headers** (array) An array of additional HTTP headers.
   * **response_headers** (boolean) Return the full request, including response headers (regardless of HTTP status). The default is ``false``.
   * **ssl_verify_peer** (boolean) Verify SSL peer. The default is ``true``.
   * **ssl_verify_host** (boolean) Verify certificate hostname (CN). The default is ``false``.
   * **ssl_default_ca** (boolean) Load additional TLS certificates (ca_root_nss). The default is ``false``.
   * **background** (boolean) Perform request in the background. In which case this function returns ``None``. The default is ``false``.
   * **background_hash** (number) Assign this request to a specific queue. If this value is higher than the number of queues, it's chosen by modulus. The default is queue ``0``.

Mail
----

.. function:: smtp_lookup_rcpt(server, sender, recipient, [options])

  Check if sender is allowed to send mail to recipient.

  :param server: array with server settings or mailtransport profile
  :type server: string or array
  :param string sender: the sender (MAIL FROM)
  :param string recipient: the recipient (RCPT TO)
  :param array options: options array
  :return: ``1`` if the command succeeded, ``0`` if the command failed and ``-1`` if an error occurred. The ``error_code`` option may change this behavior.
  :rtype: number or array

  The following server settings are available in the server array.

   * **host** (string) IP-address or hostname. **required**
   * **port** (number) TCP port. The default is ``25``.
   * **helo** (string) The default is to use the system hostname.
   * **sourceip** (string) Explicitly bind a ``netaddr:X``. The default is ``auto``.
   * **sasl_username** (string) If specified issue a AUTH LOGIN before RCPT TO.
   * **sasl_password** (string) If specified issue a AUTH LOGIN before RCPT TO.
   * **tls** (string) Use any of the following TLS modes; ``disabled``, ``optional``, ``optional_verify``, ``require`` or ``require_verify``. The default is ``disabled``.

  The following options are available in the options array.

   * **error_code** (boolean) If error_code is true and associative array with "error_code" and "error_message" is returned. The default is ``false``.

.. function:: smtp_lookup_auth(server, username, password)

  Try to authenticate the username against a SMTP server.

  :param server: array with server settings or mailtransport profile
  :type server: string or array
  :param string username: username
  :param string password: password
  :param array options: options array
  :return: ``1`` if the authentication succeeded, ``0`` if the authentication failed and ``-1`` if an error occurred.
  :rtype: number

  The following server settings are available in the server array.

   * **host** (string) IP-address or hostname. **required**
   * **port** (number) TCP port. The default is ``25``.
   * **helo** (string) The default is to use the system hostname.
   * **sourceip** (string) Explicitly bind a ``netaddr:X``. The default is ``auto``.
   * **tls** (string) Use any of the following TLS modes; ``disabled``, ``optional``, ``optional_verify``, ``require`` or ``require_verify``. The default is ``disabled``.

.. function:: dovecot_lookup_auth(options, username, password)

  Try to authenticate the username against a dovecot server.

  :param array options: options array
  :param string username: username
  :param string password: password
  :return: ``1`` if the authentication succeeded, ``0`` if the authentication failed and ``-1`` if an error occurred.
  :rtype: number

  The following options are available in the options array.

   * **host** (string) IP-address or hostname of the dovecot server. **required**
   * **port** (number) TCP port. **required**
   * **timeout** (number) Timeout in seconds. The default is ``5`` seconds.

   There are also some protocol specific flags that may be configured.

	   * **service** (string) Service name to identify this request. The default is ``smtp``.
	   * **rip** (string) The IP-address of the client (remote IP).
	   * **lip** (string) The IP-address of the Halon (local IP).
	   * **secured** (boolean) Set to true if the client has tlsstarted. The default is ``false``.

.. function:: ldap_search(profile, lookup, [override])

  Query an LDAP server for lookup and return all LDAP entries found.

  :param string profile: ldap profile
  :param string lookup: query that will be inserted into the ldap query (ldapescaped)
  :param array override: options array
  :return: an array with LDAP entries or ``-1`` if an error occurred.
  :rtype: array or number

  The following overrides are available in the override array.

   * **host** (string) IP-address or hostname.
   * **username** (string) LDAP username.
   * **password** (string) LDAP password.
   * **base** (string) LDAP base.
   * **query** (string) LDAP query (unescaped).

.. function:: ldap_bind(profile, username, password, [override])

  Try to bind (authenticate) against an LDAP server.

  :param string server: ldap profile
  :param string username: LDAP username
  :param string password: LDAP password
  :param array override: options array
  :return: ``1`` if the authentication succeeded, ``0`` if the authentication failed and ``-1`` if an error occurred.
  :rtype: number

  The following overrides are available in the override array.

   * **host** (string) IP-address or hostname.

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

  Send a authorization request to a TACACS+ server.

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

.. function:: dnsbl(ip, hostname, [resolvers, [timeout = 5]])

  Query the resolvers for the DNSBL status of an address. If no resolvers are given, the system default is used.

  :param string ip: IP or IPv6 address to check
  :param string hostname: in DNSBL list
  :param array resolvers: list of resolvers
  :param number timeout: timeout in seconds
  :return: list of IP addresses
  :rtype: array

  This function works by reversing the IP addresses octets and appending to the hostname parameter.

.. function:: spf(ip, helo, domain)

  Check the SPF status of the senderdomain.

  :param string ip: IP or IPv6 address to check
  :param string helo: HELO/EHLO host name
  :param string domain: domain too lookup
  :return: ``0`` if the addresses passed, ``20`` for softfail, ``50`` if the status is unknown and ``100`` if the spf failed.
  :rtype: number

.. function:: globalview(ip)

  Check the Cyren Glovalview reputation for an IP.

  :param string ip: IP or IPv6 address to check
  :return: the recommended action to take for the ip ``accept``, ``tempfail`` or ``permfail``.
  :rtype: string

.. function:: mail(sender, recipient, subject, body, [options])

  Send an email to recipient.

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
   * **plaintext** (boolean) Send message as `plain/text` (default is `text/html`). The default is ``false``.
   * **rawbody** (boolean) Instead of using a template, send body as raw text. The default is ``false``.
   * **headers** (array) Add additional message headers (KVP).
   * **metadata** (array) Add additional metadata to the message (KVP).

   If sending the message with custom templates.

	   * **variables** (array) Set additional to the template engine (KVP).
	   * **template** (array) Choose template. The default is ``internal/en_EN``.
	   * **templatefile** (array) Choose template file. The default is ``plain_mail.html``.

  .. code-block:: hsl

	  mail("postmaster@example.com", "support@halon.se", "Lunch", "How about lunch on Friday?");

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

  .. note::

  	If you want your log message to appear when the message log is viewed (as it does with :func:`echo`, you should prefix the message parameter with ``"[$messageid] "``.

.. function:: stat(name, legends)

  Stat values into a graph

  :param string name: name of the graph
  :param array legends: key value pair of legends
  :rtype: none

  Values stat'ed are available using the statList SOAP API, visual graphs and SNMP.

  .. code-block:: hsl

	  $fam4 = 0; $fam6 = 0;
	  if (in_network($senderip, "0.0.0.0/0")) { $fam4 = 1; } else { $fam6 = 1; }
	  stat("ip-family", ["ipv4" => $fam4, "ipv6" => $fam6]);

  .. note::

  	You can only use "a-z", "0-9" and "-" in the names and legends when using the stat function. For example, uppercase letters are not allowed.

.. function:: in_network(ip, network)

  Returns true if ip is in the subnet of network.

  :param string ip: IP or IPv6 address
  :param string network: address, subnet or range.
  :return: true if ip is in network
  :rtype: boolean

  .. code-block:: hsl

	in_network("127.0.0.1", "127.0.0.1/8");
	in_network("127.0.0.1", "127.0.0.0-127.255.255.255");
	in_network("127.0.0.1", "127.0.0.1");

.. function:: rate(namespace, entry, count, interval)

  Check or account for the rate of entry in namespace during the last interval.

  :param string namespace: the namespace
  :param string entry: an entry
  :param number count: the count
  :param number interval: the interval in seconds
  :return: if count is greater than zero, it will increase the rate and return ``true``, or return ``false`` if the limit is exceeded. If count is zero ``0``, it will return the number of items during the last ``interval``.
  :rtype: number

  .. code-block:: hsl

	  if (rate("outbound", $saslusername, 3, 60) == false) {
			  Reject("User is only allowed to send 3 messages per minute");
	  }

  .. note::

  	Rates are shared between all contexts, and may also be synchronized in clusters.

String
------

.. function:: chr(c)

  Returns ASCII character of number c.

  :param number c: the ASCII number
  :return: string from ASCII value c
  :rtype: string

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
  :param string start: the start position
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
  :return: returns matches if no result is found, an empty array is returned.
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
  :param string replace: the pattern to replace with
  :param string subject: the string acted upon
  :param number limit: max occurrences to replace (`0` equals `unlimited`)
  :return: return subject with the replacements done
  :rtype: string

  In `replace` matches are available using ``$0`` to ``$n``. ``$0`` will be the entire match, and ``$1`` (and forward) each match group.

.. code-block:: hsl

	echo pcre_replace("\\[link](.*?)\\[/link]",
	        "<a href=\"$1\">$1</a>",
			        "[link]http://halon.se[/link]");
	// <a href="http://halon.se">http://halon.se</a>

	echo pcre_replace("\\d", "($0)", "foo1bar2baz");
	// foo(1)bar(2)baz 

