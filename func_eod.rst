MIME and attachments
^^^^^^^^^^^^^^^^^^^^

.. function:: GetMailFile([options])

  Return a :class:`File` class to the current mail file.

  :param array options: an options array
  :return: A File class to the current mail file.
  :rtype: File

  The following options are available in the options array.

   * **changes** (boolean) Include changes done to the original message. The default is ``false``.

.. class:: MIME(partid)

  :param string partid: the part id

  Working with MIME parts is done using MIME objects. To instantiate a reference to the root MIME part object call the :class:`~data.MIME` function with the string literal `"0"` (zero) as the argument.

  .. warning::

    If you call the :class:`MIME` function **without** arguments (partid), the standard library's :class:`MIME` object will be created instead.

  .. code-block:: hsl

	MIME("0")->appendPart(
		MIME()
			->setType("multipart/alternative")
			->appendPart(
				MIME()
					->setType("text/plain")
					->setBody("This is a custom footer")
				)
			->appendPart(
				MIME()
					->setType("multipart/related")
					->appendPart(
						MIME()
							->setType("text/html")
							->setBody("This is a custom footer with an image <img src='cid:logo.png'>")
					)
					->appendPart(
						MIME()
							->setType("image/png")
							->addHeader("Content-ID", "logo.png")
							->setBody(
								cache [ "ttl" => 3600 * 24 * 7 ]
									http("https://pbs.twimg.com/profile_images/656816032930119680/52m1eugJ.jpg")
							)
					)
				)
	);

  .. note::

    Changes done to any MIME object will **not** be reflected on consecutive calls to "get" functions, however they will be applied to the message upon delivery.

  .. function:: MIME.reset()

	  Undo all changes on the message. Only works on the root.

	  :return: number of changes discarded
	  :rtype: number

	  .. code-block:: hsl

		MIME("0")->reset();

  .. function:: MIME.snapshot()

    Take a snapshot of the current state of the MIME object (to be used with :func:`MIME.restore`). Only works on the root.

    :return: snapshot id
    :rtype: number

    .. code-block:: hsl

      $id = MIME("0")->snapshot();

  .. function:: MIME.restore(id)

    Restore to a snapshot (to be used with :func:`MIME.snapshot`). Only works on the root.

    :param number id: snapshot id
    :return: success
    :rtype: boolean

    .. code-block:: hsl

      MIME("0")->restore($id);

  .. function:: MIME.getID()

	  Return the MIME part's ID. This ID can be used to instantiate a new :class:`~data.MIME` object.

	  :return: part id
	  :rtype: string

  .. function:: MIME.getSize()

	  return the mime part's size in bytes.

	  :return: size in bytes
	  :rtype: number

  .. function:: MIME.getFileName()

	  Return the MIME part's file name (if it has one).

	  :return: file name
	  :rtype: string (or none)

  .. function:: MIME.getType()

	  Return the MIME part's `Content-Type`'s type field (eg. `text/plain`).

	  :return: content type
	  :rtype: string (or none)

  .. function:: MIME.getHeader(name, [options])

	  Return the value of a header (if multiple headers with the same name exists, the first will be returned). If no header is found, the type `none` is returned. The name is not case sensitive.

	  :param string name: name of the header
	  :param array options: an options array
	  :return: header value
	  :rtype: string (or none)

	  The following options are available in the options array.

	   * **index** (number) The index of the header, from the top, starting at zero. The default is ``0``.
	   * **field** (boolean) Get the header field as is (including the name). The default is ``false``.

	  .. code-block:: hsl

		if (is_string($contentid = $part->getHeader("Content-ID")))
			echo "Content-ID is $contentid";

	  .. note::

		The ``getHeader`` function family will return headers as a UTF-8 string with all MIME encoded-words decoded (`=?charset?encoding?data?=`). However even if headers must be in 7-bit ASCII, some senders do not conform to this and do send headers with different charset encodings. In those cases we (1) Use the MIME-parts "Content-Type" headers charset when converting to UTF-8. (2) If there is no charset information available we use a statistical charset detection function. (3) We just pretend it to be US-ASCII and covert it to UTF-8 anyway (guaranteeing the result will be valid UTF-8).

  .. function:: MIME.getHeaders(name, [options])

	  Return a list of header values. If no header is found, an empty list is returned. The name is not case sensitive.

	  :param string name: name of the header
	  :param array options: an options array
	  :return: header values
	  :rtype: array of string

	  The following options are available in the options array.

	   * **field** (boolean) Get the header field as is (including the name). The default is ``false``.

	  .. code-block:: hsl

		echo "Received headers: ".count(MIME("0")->getHeaders("Received"));

  .. function:: MIME.getHeaderNames()

	  Return a list of all header names, from the top. The names are in lower case.

	  :return: header names
	  :rtype: array of string

  .. function:: MIME.setHeader(name, value, [options])

	  Overwrite existing header(s) or create a new header. The name is not case sensitive.

	  :param string name: name of the header
	  :param string value: value of the header
	  :param array options: an options array
	  :return: number of headers changed
	  :rtype: number

	  The following options are available in the options array.

	   * **index** (number) The index of the header, from the top, starting at zero.
	   * **encode** (number) Refold and encode the header. The default is ``true``.

  .. function:: MIME.addHeader(name, value, [options])

	  Add a new header (at the top of the message).

	  :param string name: name of the header
	  :param string value: value of the header
	  :param array options: an options array
	  :rtype: none

	  The following options are available in the options array.

	   * **encode** (number) Refold and encode the header. The default is ``true``.

  .. function:: MIME.delHeader(name, [options])

	  Delete all headers by the name. The name is not case sensitive.

	  :param string name: name of the header
	  :param array options: an options array
	  :return: number of headers deleted
	  :rtype: number

	  The following options are available in the options array.

	   * **index** (number) The index of the header, from the top, starting at zero.

  .. function:: MIME.remove()

	  Remove this MIME part.

	  :rtype: none

  .. function:: MIME.getBody([options])

	  Get the body (content) of a MIME part. The content will be decoded according to the `Content-Transfer-Encoding` header. If the body size is bigger than 1 MiB, the type `none` is returned.

	  :param array options: an options array
	  :return: the body content
	  :rtype: string (or none)

	  The following options are available in the options array.

	   * **encode** (number) Encode the body accoding to the "Content-Transfer-Encoding" header. The default is ``true``.

	  .. note::

		The ``getBody`` function will decode using the "Content-Transfer-Encoding" header. It will not do any character set encoding, hence the data can be in any character set encoding.

  .. function:: MIME.setBody(data)

	  Set the body (content) of a MIME part. If the body argument is bigger than 1 MiB (or an another error occurred), the type `none` is returned. The MIME parts encoding (`Content-Transfer-Encoding`) will be changed to the best readable match, that can be either `7bit`, `quoted-printable` or `base64` and the data will encoded as such.

	  :param string data: the body content
	  :return: this
	  :rtype: MIME (or none)

  .. function:: MIME.prependPart(part)

	  Add a MIME part before this part.

	  :param MIME part: a :class:`MIME` part
	  :return: this
	  :rtype: MIME

  .. function:: MIME.appendPart(part)

	  Add a MIME part after this part.

	  :param MIME part: a :class:`MIME` part
	  :return: this
	  :rtype: MIME

  .. function:: MIME.replacePart(part)

	  Replace the current MIME part.

	  :param MIME part: a :class:`MIME` part
	  :rtype: none

  .. function:: MIME.findByType(type)

	  Find descendant parts (on any depth) based on their `Content-Type`.

	  :param string type: type as regex
	  :return: parts
	  :rtype: array of :class:`~data.MIME` objects

  .. function:: MIME.findByFileName(filename)

	  Find descendant parts (on any depth) based on their file name.

	  :param string filename: filename as regex
	  :return: parts
	  :rtype: array of :class:`~data.MIME` objects

  .. function:: MIME.getParts()

	  Return child parts.

	  :return: parts
	  :rtype: array of :class:`~data.MIME` objects

DKIM
^^^^
These are DKIM-related functions, including DMARC. Other modules, such as ARC, is available in the `authentication script library <https://github.com/halon/hsl-examples/tree/master/authentication>`_.

.. function:: ScanDMARC()

  Returns the `DMARC <https://docs.halon.io/go/dmarc>`_ policy to apply to the message for the From-address. It will return an associative array containing the domain as result. If the domain cannot be properly extracted or missing an error message will be returned.

  :return: associative array containing the domain and result or an error.
  :rtype: array or string

  ================================== ==========
  "permerror"                        An unknown error occurred (more details may be available in the log)
  ["example.com" => "temperror"]     A temporary error occurred (but the domain was known)
  ["example.com" => "policy_absent"] No DMARC policy for domain
  ["example.com" => "pass"]          The DMARC passed
  ["example.com" => "none"]          The policy resulted in none
  ["example.com" => "reject"]        The policy resulted in reject
  ["example.com" => "quarantine"]    The policy resulted in quarantine
  ================================== ==========

.. function:: DKIMSign(selector, domain, key, [options])

  Sign the message using `DKIM <https://docs.halon.io/go/dkim>`_.

  :param string selector: selector to use when signing
  :param string domain: domain to use when signing
  :param string key: private key to use, either ``pki:X`` or a private RSA key in PEM format.
  :param array options: options array
  :return: true if the message could be signed
  :rtype: boolean

  The following options are available in the options array.

   * **canonicalization_header** (string) body canonicalization (``simple`` or ``relaxed``). The default is ``relaxed``.
   * **canonicalization_body** (string) body canonicalization (``simple`` or ``relaxed``). The default is ``relaxed``.
   * **algorithm** (string) algorithm to hash the message with (``rsa-sha1``, ``rsa-sha256`` or ``ed25519-sha256``). The default is ``rsa-sha256``.
   * **additional_headers** (array) additional headers to sign in addition to those recommended by the RFC.
   * **oversign_headers** (array) headers to oversign. The default is ``from``.
   * **headers** (array) headers to sign. The default is to sign all headers recommended by the RFC.
   * **discard_changes** (boolean) Discard any changes to the original message before signing. The default is ``false``.
   * **return_header** (boolean) Return the DKIM signature as a string, instead of adding it to the message. The default is ``false``.
   * **arc** (boolean) Create an ARC-Message-Signature header. The default is ``false``.

  .. note::

   If `return_header` is used, you need to add the header yourself without refolding.

	  .. code-block:: hsl

		$dkimsig = DKIMSign("selector", "example.com", $key, ["return_header" => true]);
		AddHeader("DKIM-Signature", $dkimsig, false); // without refolding

.. function:: DKIMVerify(headerfield, [options]])

  DKIM verify a `DKIM-Signature` or `ARC-Message-Signature` header. The header should include both the header name and value (unmodified).

  :param string headerfield: the header to verify
  :param array options: options array
  :return: associative array containing the result.
  :rtype: array

  The following options are available in the options array.

   * **timeout** (number) the timeout (per DNS query). The default is ``5``.
   * **dns_function** (function) a custom DNS function. The default is to use the built in.

  The DNS function will be called with the hostname (eg. `2018._domainkeys.example.com`) for which a DKIM record should be returned. The result must be an array containing either an ``error`` field (``permerror`` or ``temperror``) or a ``result`` field with a DKIM TXT record as string.

  The resulting array always contains a ``result`` field of either ``pass``, ``permerror`` or ``temperror``. In case of an error the reason is included in an ``error`` field. If the header was successfully parsed (regardless of the result) a ``tags`` field will be included. 

.. function:: DKIMSDID([explicitdomains, [options]])

  Returns the SDID (Signing Domain IDentifier) status from the `DKIM <https://docs.halon.io/go/dkim>`_ header of the message.

  :param array explicitdomains: array of explicit domains to check, empty array for all
  :param array options: options array
  :return: associative array containing the domain and result.
  :rtype: array

  The following options are available in the options array.

   * **signature_limit** (number) signatures to verify. The default is ``5``.
   * **timeout** (number) the timeout (per DNS query). The default is ``5``.
   * **dns_function** (function) a custom DNS function. The default is to use the built in.

  The DNS function will be called with the hostname (eg. `2018._domainkeys.example.com`) for which a DKIM record should be returned. The result must be an array containing either an ``error`` field (``permerror`` or ``temperror``) or a ``result`` field with a DKIM TXT record as string.

  ========= ===========
  Result    Description
  ========= ===========
  skip      The validation of the DKIM record was not checked (due to the domain filter or signature limit)
  pass      The message was signed and the signature(s) passed verification.
  fail      The message was signed but they failed the verification.
  temperror A later attempt may produce a final result.
  permerror A later attempt is unlikely to produce a final result.
  ========= ===========

Embedded content scanning
^^^^^^^^^^^^^^^^^^^^^^^^^
These functions scan the message file using various engines.
While the DLP engine ``dlpd`` is included in all software packages, the embedded anti-spam and anti-virus engines are only available in the full system distribution (virtual machine) package.
All connectors are available in the `script library <https://github.com/halon/hsl-examples/>`_.

.. function:: ScanDLP([patterns, [options]])

  Scan a message using the builtin `DLP <https://docs.halon.io/go/dlp>`_ engine.

  :param array patterns: array of pre-configured rules or an array of custom rules
  :param array options: options array
  :return: all patterns found (may include `ERR_` rules even if not explicitly given in the `patterns` argument)
  :rtype: array

  The following options are available in the options array.

   * **stop_on_match** (boolean) processing the mail when one match (of the requested type) is found. The default is ``false``.
   * **timeout** (number) set an approximate timeout time in seconds. The default in no timeout.
   * **recursion_limit** (number) how deep to dig through MIME trees, archive files (such as ZIP), etc. The default is ``9``.
   * **partid** (boolean) return a data structure with the partid where the pattern is found. The default is ``false``.
   * **extended_result** (boolean) Return extended results. The default is ``false``.

  The following results are available in the extended results array.

	   * **rules** (array) The rules matched

	   On error the following items are available.

	   * **error** (boolean) Indicates if there was an error during the scanning

  The patterns array may either be an array of pre-configured rules by name.

  .. code-block:: hsl

	["RULE1", "RULE2", ...]

  Or a custom rule with the patterns provided. A custom rule may contain multiple types (eg. `filename`, `sha1hash` etc.) with multiple patterns each. The available types may be extracted from the DLP configuration.

  .. code-block:: hsl

	["RULE1" => ["filename" => ["/\\.exe$/i", "/\\.zip$/i"], "sha1hash" => ["..."]], ...]

  .. warning::

	Do not allow untrusted users to add custom regular expression, since not all regular expressions are safe. All user data should be escaped using :func:`pcre_quote` before compiled into a regular expression.

  There are some builtin rules which may occur.

  ========================== ===========
  Builtin rules              Description
  ========================== ===========
  ERR_UNKNOWN_ERROR          An unknown error occurred (more details may be available in the log)
  ERR_PASSWORD_PROTECTED     The archive is password protected
  ERR_RECURSION_LIMIT        The archive is too nested
  ========================== ===========

.. function:: ScanRPD([options])

  Scan the message using `Cyren <https://docs.halon.io/go/cyren>`_; anti-spam ``ctasd`` (RPD and LocalView) and zero-hour malware detection (VOD). It runs in either inbound or outbound mode, and it's important to configure this correctly with the `outbound` option.

  :param array options: options array
  :return: score or refid
  :rtype: number, string or array

  The following options are available in the options array.

   * **refid** (boolean) Return RefID (used to report FN and FP). The default is ``false``.
   * **outbound** (boolean) Use RPD in outbound mode. The default is ``false``.
   * **extended_result** (boolean) Return extended results. The default is ``false``.

  The following results are available in the extended results array.

	   * **refid** (string) The refid
	   * **rules** (array) The LocalView spam rules matched
	   * **spam_score** (number) The spam score
	   * **spam_class** (string) The spam class
	   * **virus_score** (number) The virus score
	   * **virus_class** (string) The virus class

	   On error the following items are available.

	   * **error** (boolean) Indicates if there was an error during the scanning

  RPD’s anti-spam classification scores and class names

  ===== ================= ===========
  Score Class             Description
  ===== ================= ===========
  0     non-spam, unknown Unknown
  10    suspect           Suspect
  40    valid-bulk        Valid bulk
  50    bulk              Bulk
  100   spam              Spam
  ===== ================= ===========

  RPD’s anti-virus classification scores and class names

  ===== ================== ===========
  Score Class              Description
  ===== ================== ===========
  0     non-virus, unknown Unknown
  50    medium             Medium probability
  100   virus, high        High probability
  ===== ================== ===========

.. function:: ScanSA([options])

  Scan the message using `SpamAssassin <https://docs.halon.io/go/distspamassassin>`_.

  :param array options: options array
  :return: score or rules
  :rtype: number or array

  The following options are available in the options array.

   * **rules** (boolean) Return rules in an associative array with scores. The default is ``false``.
   * **extended_result** (boolean) Return extended results. The default is ``false``.

  The following results are available in the extended results array.

	   * **rules** (array) The rules matched

	   On error the following items are available.

	   * **error** (boolean) Indicates if there was an error during the scanning

  ========================== ===== ===========
  Builtin rules              Score Description
  ========================== ===== ===========
  NOT_SCANNED_TOO_BIG        0     Message was to big too big to be scanned
  NOT_SCANNED_QUEUE_TOO_LONG 0     Queue was too long to SpamAssassin
  ========================== ===== ===========

  A score of `5` or higher is what most people accept to be considered spam.

.. function:: ScanKAV([options])

  Scan the message using the Sophos anti-virus.

  :param array options: options array
  :return: any viruses found
  :rtype: array

  The following options are available in the options array.

   * **extended_result** (boolean) Return extended results. The default is ``false``.

  The following results are available in the extended results array.

	   * **rules** (array) The rules matched

	   On error the following items are available.

	   * **error** (boolean) Indicates if there was an error during the scanning

.. function:: ScanCLAM([options])

  Scan the message using ClamAV anti-virus.

  :param array options: options array
  :return: any viruses found
  :rtype: array

  The following options are available in the options array.

   * **extended_result** (boolean) Return extended results. The default is ``false``.

  The following results are available in the extended results array.

	   * **rules** (array) The rules matched

	   On error the following items are available.

	   * **error** (boolean) Indicates if there was an error during the scanning

Miscellaneous
^^^^^^^^^^^^^

.. function:: GetAddressList(value)

 Extract addresses from a header value, often used with `From`, `To` and `CC` headers.

 :param string value: value to extract email addresses from
 :return: email addresses
 :rtype: array

 .. code-block:: hsl

 	$headerSender = GetAddressList(GetHeader("From"))[0]; // first email address in From header

.. function:: GetMailQueueMetric([options])

  Return metric information about the mail queue, it can be used to enforce quotas.

  :param array options: options array
  :rtype: number

.. include:: func_getmailqueuemetric.rst

.. include:: func_gettls.rst