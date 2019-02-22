DATA, MIME and attachments
^^^^^^^^^^^^^^^^^^^^^^^^^^

.. function:: GetMailMessage()

  :return: A MailMessage reference
  :rtype: :cpp:class:`MailMessage`

  This is a "factory function" which returns a :cpp:class:`MailMessage` object reference to the DATA (message) received as the result of the End-of-DATA command.

  .. code-block:: hsl

	GetMailMessage()->appendPart(
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

.. cpp:class:: MailMessage : MIMEPart

  This class extends the :cpp:class:`MIMEPart` class, all instances of this class automatically holds a reference to the top level MIMEPart object.

  .. note::

    This class can only be instantiated using the :func:`GetMailMessage` function. 

  .. function:: MailMessage.reset()

	  Undo all changes on the message.

	  :return: number of changes discarded
	  :rtype: number

	  .. code-block:: hsl

		GetMailMessage()->reset();

  .. function:: MailMessage.snapshot()

    Take a snapshot of the current state of the MIME object (to be used with :func:`MailMessage.restore`).

    :return: the snapshot id
    :rtype: number

    .. code-block:: hsl

      $id = GetMailMessage()->snapshot();

  .. function:: MailMessage.restore(id)

    Restore to a snapshot (to be used with :func:`MailMessage.snapshot`).

    :param number id: snapshot id
    :return: if restore was successful
    :rtype: boolean

    .. code-block:: hsl

      GetMailMessage()->restore($id);

  .. function:: MailMessage.toFile()

    Return a :class:`File` class for the current MIME object (with all changes applied).

    :return: A File class for the current MIME object.
    :rtype: :class:`File`

  .. function:: MailMessage.signDKIM(selector, domain, key, [options])

    Sign the message using `DKIM <https://docs.halon.io/go/dkim>`_. On error None is returned.

    :param string selector: selector to use when signing
    :param string domain: domain to use when signing
    :param string key: private key to use, either ``pki:X`` or a private RSA key in PEM format.
    :param array options: options array
    :return: this
    :rtype: :cpp:class:`MailMessage`

    The following options are available in the options array.

      * **canonicalization_header** (string) body canonicalization (``simple`` or ``relaxed``). The default is ``relaxed``.
      * **canonicalization_body** (string) body canonicalization (``simple`` or ``relaxed``). The default is ``relaxed``.
      * **algorithm** (string) algorithm to hash the message with (``rsa-sha1``, ``rsa-sha256`` or ``ed25519-sha256``). The default is ``rsa-sha256``.
      * **additional_headers** (array) additional headers to sign in addition to those recommended by the RFC.
      * **oversign_headers** (array) headers to oversign. The default is ``from``.
      * **headers** (array) headers to sign. The default is to sign all headers recommended by the RFC.
      * **id** (boolean) If the key is expected to be in the ``pki:X`` format. The default is auto detect.
      * **return_header** (boolean) Return the DKIM signature as a string, instead of adding it to the message. The default is ``false``.
      * **arc** (boolean) Create an ARC-Message-Signature header. The default is ``false``.

    .. note::

      If `return_header` is used, you need to add the header yourself without refolding.

      .. code-block:: hsl

        $dkimsig = $message->signDKIM("selector", "example.com", $key, ["return_header" => true]);
        $message->addHeader("DKIM-Signature", $dkimsig, ["encode" => false]);

  .. function:: MailMessage.verifyDKIM(headerfield, [options]])

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

  .. function:: MailMessage.send(server, sender, recipients)

    Try to send the message to the server.

    :param server: array with server settings or transport profile ID
    :type server: string or array
    :param sender: the sender (`MAIL FROM`), an address object
    :type sender: array
    :param recipients: the recipient (`RCPT TO`), an array of address objects
    :type recipients: array
    :return: associative array containing the result or an error
    :rtype: array

    The address object should contain a ``address`` field (either a string or a tuple with localpart and domain) and optionally a ``params`` field as key-values (to be sent in `MAIL FROM` or `RCPT TO`).

    .. code-block:: hsl

      $result = $message->send(
          ["host" => "10.2.0.1", "tls" => "require"],
          ["address" => ""],
          [
              ["address" => "chris@example.com", "params" => ["NOTIFY" => "DELAY"]],
              ["address" => ["charlie", "example.com"]]
          ]);
      
      if (isset($result["error"]))
      {
          $error = $result["error"];
          if (isset($error["code"]))
          {
              if ($error["code"] >= 500 and $error["code"] <= 599)
                  Reject($error["reason"],
                      ["reply_codes" => ["code" => $error["code"], "enhanced" => $error["enhanced"]]]);
              else
                  Defer($error["reason"],
                      ["reply_codes" => ["code" => $error["code"], "enhanced" => $error["enhanced"]]]);
          }
          else
          {
              Defer();
          }
      } else {
          Accept($result["result"]["reason"],
              ["reply_codes" => ["code" => $result["result"]["code"], "enhanced" => $result["result"]["enhanced"]]]);
      }

    .. include:: func_serverarray.rst

    A successful result from this function contains a ``result`` field. This ``result`` field contains a ``reason`` field (array of strings) containing the SMTP reponse (from the server) and a ``code`` (number) field containg the SMTP status code, optionally a ``enhanced`` (array of three numbers) field containg the SMTP enhanced status code.
    
    An error result from this function contains an ``error`` field. This ``error`` field contains a ``temporary`` (boolean) field to indicate if the error may be transient and a ``reason`` field (array of strings) containing either the SMTP response (from the server) or a list of errors. In case the error was due to a SMTP response a ``code`` (number) field containg the SMTP status code will be included and optionally a ``enhanced`` (array of three numbers) field containg the SMTP enhanced status code.

    A ``tls`` field will always be included, to indicate if the connection had TLS enabled. 
    
.. cpp:class:: MIMEPart

  This class represent a MIME part in the MIME tree parsed as a result of the End-of-DATA command.

  .. note::

    This class can only be accessed through the extended :cpp:class:`MailMessage` class or from functions returning this object type eg. :func:`MIMEPart.getParts`. 

  .. note::

    Changes done to any MIME object will **not** be reflected on consecutive calls to "get" functions, however they will be applied to the message upon delivery.

  .. function:: MIMEPart.getID()

	  Return the MIME part's ID.

	  :return: part id
	  :rtype: string

  .. function:: MIMEPart.getSize()

	  Return the MIME part's size in bytes.

	  :return: size in bytes
	  :rtype: number

  .. function:: MIMEPart.getFileName()

	  Return the MIME part's file name (if it has one).

	  :return: file name
	  :rtype: string (or none)

  .. function:: MIMEPart.getType()

	  Return the MIME part's `Content-Type`'s type field (eg. `text/plain`).

	  :return: content type
	  :rtype: string (or none)

  .. function:: MIMEPart.getHeader(name, [options])

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

		The :func:`MIMEPart.getHeader` function family will return headers as a UTF-8 string with all MIME encoded-words decoded (`=?charset?encoding?data?=`). However even if headers must be in 7-bit ASCII, some senders do not conform to this and do send headers with different charset encodings. In those cases we (1) Use the MIME-parts "Content-Type" headers charset when converting to UTF-8. (2) If there is no charset information available we use a statistical charset detection function. (3) We just pretend it to be US-ASCII and covert it to UTF-8 anyway (guaranteeing the result will be valid UTF-8).

  .. function:: MIMEPart.getHeaders(name, [options])

	  Return a list of header values. If no header is found, an empty list is returned. The name is not case sensitive.

	  :param string name: name of the header
	  :param array options: an options array
	  :return: header values
	  :rtype: array of string

	  The following options are available in the options array.

	   * **field** (boolean) Get the header field as is (including the name). The default is ``false``.

	  .. code-block:: hsl

		echo "Received headers: ".count(DATA()->getHeaders("Received"));

  .. function:: MIMEPart.getHeaderNames()

	  Return a list of all header names, from the top. The names are in lower case.

	  :return: header names
	  :rtype: array of string

  .. function:: MIMEPart.setHeader(name, value, [options])

	  Overwrite existing header(s) or create a new header. The name is not case sensitive.

	  :param string name: name of the header
	  :param string value: value of the header
	  :param array options: an options array
	  :return: number of headers changed
	  :rtype: number

	  The following options are available in the options array.

	   * **index** (number) The index of the header, from the top, starting at zero.
	   * **encode** (boolean) Refold and encode the header. The default is ``true``.

  .. function:: MIMEPart.addHeader(name, value, [options])

	  Add a new header (at the top of the message).

	  :param string name: name of the header
	  :param string value: value of the header
	  :param array options: an options array
	  :rtype: none

	  The following options are available in the options array.

	   * **encode** (boolean) Refold and encode the header. The default is ``true``.

  .. function:: MIMEPart.delHeader(name, [options])

	  Delete all headers by the name. The name is not case sensitive.

	  :param string name: name of the header
	  :param array options: an options array
	  :return: number of headers deleted
	  :rtype: number

	  The following options are available in the options array.

	   * **index** (number) The index of the header, from the top, starting at zero.

  .. function:: MIMEPart.remove()

	  Remove this MIME part.

	  :rtype: none

  .. function:: MIMEPart.getBody([options])

	  Get the body (content) of a MIME part. The content will be decoded according to the `Content-Transfer-Encoding` header.

	  :param array options: an options array
	  :return: the body content
	  :rtype: string (or none)

	  The following options are available in the options array.

	   * **decode** (boolean) Decode the body accoding to the "Content-Transfer-Encoding" header. The default is ``true``.

	  .. note::

		This function will decode using the "Content-Transfer-Encoding" header. It will not do any character set conversion, hence the data can be in any character set encoding.

  .. function:: MIMEPart.setBody(data)

	  Set the body (content) of a MIME part. If the body argument is bigger than 1 MiB (or an another error occurred), the type `none` is returned. The MIME parts encoding (`Content-Transfer-Encoding`) will be changed to the best readable match, that can be either `7bit`, `quoted-printable` or `base64` and the data will encoded as such.

	  :param string data: the body content
	  :return: this

  .. function:: MIMEPart.prependPart(part, [options])

	  Add a MIME part before this part.

	  :param MIME part: a :class:`MIME` or :cpp:class:`MIMEPart` object
	  :param array options: an options array
	  :return: this

	  The following options are available in the options array.

	   * **type** (string) The multipart content type to use. The default is ``multipart/mixed``.

  .. function:: MIMEPart.appendPart(part, [options])

	  Add a MIME part after this part.

	  :param MIME part: a :class:`MIME` or :cpp:class:`MIMEPart` object
	  :param array options: an options array
	  :return: this

	  The following options are available in the options array.

	   * **type** (string) The multipart content type to use. The default is ``multipart/mixed``.

  .. function:: MIMEPart.replacePart(part)

	  Replace the current MIME part.

	  :param MIME part: a :class:`MIME` or :cpp:class:`MIMEPart` object
	  :rtype: none

  .. function:: MIMEPart.findByType(type)

	  Find descendant parts (on any depth) based on their `Content-Type`.

	  :param string type: type as regex
	  :return: parts
	  :rtype: array of :cpp:class:`MIMEPart` objects

  .. function:: MIMEPart.findByFileName(filename)

	  Find descendant parts (on any depth) based on their file name.

	  :param string filename: filename as regex
	  :return: parts
	  :rtype: array of :cpp:class:`MIMEPart` objects

  .. function:: MIMEPart.getParts()

	  Return child parts.

	  :return: parts
	  :rtype: array of :cpp:class:`MIMEPart` objects

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

.. function:: XKIMSDID([explicitdomains, [options]])

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

.. function:: GetMailQueueMetric([options])

  Return metric information about the mail queue, it can be used to enforce quotas.

  :param array options: options array
  :rtype: number

.. include:: func_getmailqueuemetric.rst

.. include:: func_gettls.rst
