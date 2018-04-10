.. module:: data

DATA
====

The DATA context is executed once for every recipient when the message is fully received (but not yet accepted). If multiple types of actions are performed, the response message (sent back to the client) will be choosen in the order of Reject, Defer, Quarantine, Delete, Deliver.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for each recipient (on a message).

Connection
^^^^^^^^^^

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$senderip         string  "192.168.1.11"             IP address of the connected client
$senderport       number  41666                      TCP port of connected client
$serverip         string  "10.0.0.1"                 IP address of the mailserver
$serverport       number  25                         TCP port of the mailserver
$serverid         string  "mailserver\:1"            ID of the mailserver profile
$senderhelo       string  "mail.example.com"         HELO message of sender
$tlsstarted       boolean false                      Whether or not the SMTP session is using TLS
$saslauthed       boolean true                       Whether or not the SMTP session is authenticated (SASL)
$saslusername     string  "mailuser"                 SASL username
================= ======= ========================== ===========

These are the writable pre-defined variables available.

================= ======= ===========
Variable          Type    Description
================= ======= ===========
$context          any     Connection-bound variable
================= ======= ===========

Transaction
^^^^^^^^^^^

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$messageid        string  "18c190a3-93f-47d7-bd..."  ID of the message
$senderdomain     string  "example.org"              Domain part of sender's address (envelope)
$sender           string  "test\@example.org"        E-mail address of sender (envelope)
$senderparams     array   ["SIZE" => "2048", ... ]   Sender parameters to the envelope address
$recipientdomains array   ["example.com", ...]       List of all domain part of all recipient addresses (envelope)
$recipients       array   ["test\@example.com", ...] List of all recipient addresses (envelope), in order of scanning
================= ======= ========================== ===========

Arguments
^^^^^^^^^

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$recipientdomain  string  "example.com"              Domain part of recipient's address (envelope)
$recipient        string  "test\@example.com"        E-mail address of recipient (envelope)
$transportid      string  "mailtransport\:1"         ID of the transport profile to be used
$actionid         number  1                          ID; incremented per message action/recipient (Deliver, Quarantine, etc.)
================= ======= ========================== ===========

Functions
---------

* **MIME and attachments** :class:`~data.MIME` :func:`GetMailFile`
* **Misc** :func:`GetAddressList` :func:`GetMailQueueMetric` :func:`GetTLS`
* **Routing** :func:`SetSender` :func:`SetRecipient` :func:`SetMailTransport` :func:`SetDelayedDeliver` :func:`SetMetaData` :func:`GetMetaData` :func:`SetSenderIP` :func:`SetSenderHELO`
* **Headers** :func:`GetHeader` :func:`GetHeaders` :func:`AddHeader` :func:`SetHeader` :func:`PrependHeader` :func:`AppendHeader` :func:`DelHeader` :func:`GetRoute` :func:`GetDSN` :func:`GetDSNHeader`
* **Actions** :func:`Deliver` :func:`Reject` :func:`Defer` :func:`Delete` :func:`Quarantine` :func:`DiscardMailDataChanges` :func:`Done`
* **Anti-spam and anti-virus** :func:`ScanRPD` :func:`ScanSA` :func:`ScanKAV` :func:`ScanCLAM` :func:`ScanDLP`
* **DKIM** :func:`ScanDMARC` :func:`DKIMSign` :func:`DKIMSDID` :func:`DKIMADSP`

Misc
^^^^

.. function:: GetAddressList(value)

 Extract addresses from a header value, often used with `From`, `To` and `CC` headers.

 :param string value: value to extract e-mail addresses from
 :return: e-mail addresses
 :rtype: array

 .. code-block:: hsl

 	$headerSender = GetAddressList(GetHeader("From"))[0]; // first e-mail address in From header

.. function:: GetMailQueueMetric(options)

  Return metric information about the mail queue, it can be used to enforce quotas.

  :param array options: options array
  :rtype: number

.. include:: func_getmailqueuemetric.rst

.. include:: func_gettls.rst

Routing
^^^^^^^

.. function:: SetSender(sender)

  Change the sender of the message.

  :param string sender: an e-mail address
  :return: sender if successful
  :rtype: string or none
  :updates: ``$sender`` and ``$senderdomain``

  .. warning::

  	This function changes the sender for all recipients. To change sender per recipient use :func:`~predelivery.SetSender` in the :doc:`Pre-delivery <predelivery>` context.

.. function:: SetRecipient(recipient)

  Changes the recipient.

  :param string recipient: an e-mail address
  :return: recipient if successful
  :rtype: string or none
  :updates: ``$recipient`` and ``$recipientdomain``

.. function:: SetMailTransport(transportid)

  Changes the transport profile.

  :param string transportid: the transportid to be used
  :rtype: none
  :updates: ``$transportid``

.. function:: SetDelayedDeliver(delay)

  If the message is queued, the first delivery attempt is delayed.

  :param number delay: delay in seconds
  :rtype: none

.. function:: SetMetaData(metadata)

  Set the metadata for the next recipient(s). The metadata must be an array with both string keys and values.

  :param array metadata: metadata to set
  :rtype: none

  .. code-block:: hsl

  	SetMetaData(["foo" => "bar", "foo2" => json_encode(["array", 123.45, false]));

  .. note::

    To work-around the data type limitation of the metadata; data can be encoded using :func:`json_encode`.

.. function:: GetMetaData()

  Get the metadata set by :func:`SetMetaData`. If no data was set, an empty array is returned.

  :return: the data set by :func:`SetMetaData`
  :rtype: array

.. function:: SetSenderIP(ip)

  Change the senders IP of the message.

  :param string ip: an IP address
  :return: ip if successful
  :rtype: string or none
  :updates: ``$senderip``

  .. note::

  	This function changes the `$senderip` for all recipients.

.. function:: SetSenderHELO(hostname)

  Change the senders HELO hostname of the message.

  :param string hostname: a hostname
  :return: hostname if successful
  :rtype: string or none
  :updates: ``$senderhelo``

  .. note::

  	This function changes the `$senderhelo` for all recipients.

Headers
^^^^^^^

.. function:: GetHeader(name, [decode = true])

  Return the value of a header (if multiple headers with the same name exists, the first will be returned). The name is not case sensitive.

  :param string name: name of the header
  :param boolean decode: if false, the header will not be decoded
  :return: header value
  :rtype: string

.. function:: GetHeaders(name, [decode = true])

  Return the value of all headers with the name. If name is boolean true, all headers will be returned. The name is not case sensitive.

  :param string name: name of the header
  :param boolean decode: if false, the header will not be decoded
  :return: headers' values
  :rtype: array

.. function:: AddHeader(name, value, [refold = true])

  Add a new header (at the top of the message).

  :param string name: name of the header
  :param string value: value of the header
  :param boolean refold: refold header to 80 characters per line
  :rtype: none

.. function:: SetHeader(name, value, [refold = true])

  Overwrite existing header(s) or create a new header. The name is not case sensitive.

  :param string name: name of the header
  :param string value: value of the header
  :param boolean refold: refold header to 80 characters per line
  :return: number of headers changed
  :rtype: number

.. function:: PrependHeader(name, value, [refold = true])

  Prepend to existing header(s) or create a new header. The name is not case sensitive.

  :param string name: name of the header
  :param string value: value of the header
  :param boolean refold: refold header to 80 characters per line
  :return: number of headers changed
  :rtype: number

.. function:: AppendHeader(name, value, [refold = true])

  Append to existing header(s) or create a new header. The name is not case sensitive.

  :param string name: name of the header
  :param string value: value of the header
  :param boolean refold: refold header to 80 characters per line
  :return: number of headers changed
  :rtype: number

.. function:: DelHeader(name)

  Delete all headers by the name. The name is not case sensitive.

  :param string name: name of the header
  :return: number of headers deleted
  :rtype: number

.. function:: GetRoute([extended_result = false])

  :param boolean extended_result: include more information
  :return: the message's `Received` header(s) parsed in a usable format
  :rtype: array

.. function:: GetDSN()

  Parse a DSN message.

  :return: information about a DSN message
  :rtype: array or false

.. function:: GetDSNHeader(name, [skip_decode = false])

  Same as GetHeader except it works on attached DSN messages. The name is not case sensitive.

  :param string name: the header
  :param boolean skip_decode: if decoding should be skipped (return raw)
  :return: the header value
  :rtype: string

Actions
^^^^^^^

.. function:: Deliver([options])

  Deliver the message.

  :param array options: an options array
  :return: doesn't return, script is terminated
  :updates: ``$actionid``

  The following options are available in the options array.

   * **recipient** (string) set the recipient. The default is ``$recipient``.
   * **transportid** (string) set the transportid. The default is ``$transportid``.
   * **metadata** (array) add additional metadata to the message (KVP). same as :func:`SetMetaData`.
   * **delay** (number) same as :func:`SetDelayedDeliver`. The default is ``0`` seconds.
   * **done** (boolean) if the function should terminate the script. Same as calling :func:`Done`. The default is ``true``.
   * **queue** (boolean) deliver the message using the delivery queue. The default is ``true``.
   * **disconnect** (boolean) disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Reject([reason, [options]])

  Reject (550) a message. If `reason` is an array or contains `\\n` it will be split into a multiline response.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated
  :updates: ``$actionid``

  The following options are available in the options array.

   * **disconnect** (boolean) disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Defer (421) a message. If `reason` is an array or contains `\\n` it will be split into a multiline response.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated
  :updates: ``$actionid``

  The following options are available in the options array.

   * **disconnect** (boolean) disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Delete()

  Delete the message (and return 250).

  :return: doesn't return, script is terminated
  :updates: ``$actionid``

.. function:: Quarantine(quarantineid, [options])

  Quarantine or `archive <http://wiki.halon.se/Archiving>`_ a message.

  :param string quarantineid: the quarantine profile
  :param array options: an options array
  :return: doesn't return, script is terminated
  :updates: ``$actionid``

  The following options are available in the options array.

   * **recipient** (string) set the recipient. The default is ``$recipient``.
   * **transportid** (string) set the transportid. The default is ``$transportid``.
   * **metadata** (array) add additional metadata to the message (KVP). same as :func:`SetMetaData`.
   * **done** (boolean) if the function should terminate the script. Same as calling :func:`Done`. The default is ``true``.
   * **reject** (boolean) if the function should return an 500 error. The default is ``true``.
   * **reason** (string) the reason to report. The default is a system generated message.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: DiscardMailDataChanges()

  Discard any content changes to the message.

  :return: number of changes discarded
  :rtype: number

.. function:: Done()

  Finishes the execution of the current recipient without doing an additional action. If a message is scanned without any action, it will be deferred.

  :return: doesn't return, script is terminated

Anti-spam and anti-virus
^^^^^^^^^^^^^^^^^^^^^^^^

.. function:: ScanRPD([options])

  Scan the message using `CYREN <http://wiki.halon.se/CYREN>`_; anti-spam (RPD and LocalView) and zero-hour malware detection (VOD). It runs in either inbound or outbound mode, and it's important to configure this correctly with the `outbound` option.

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

  ===== ================= ===========
  Score Class             Description
  ===== ================= ===========
  0     non-virus, unkown Unknown
  50    medium            Medium probability
  100   virus, high       High probability
  ===== ================= ===========

.. function:: ScanSA([options])

  Scan the message using `SpamAssassin <http://wiki.halon.se/SpamAssassin>`_.

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

  Scan the message using the commercial anti-virus.

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

  Scan the message using CLAM anti-virus.

  :param array options: options array
  :return: any viruses found
  :rtype: array

  The following options are available in the options array.

   * **extended_result** (boolean) Return extended results. The default is ``false``.

  The following results are available in the extended results array.

	   * **rules** (array) The rules matched

	   On error the following items are available.

	   * **error** (boolean) Indicates if there was an error during the scanning

.. function:: ScanDLP([patterns, [options]])

  Scan a message using the builtin `DLP <http://wiki.halon.se/DLP>`_ engine.

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

DKIM
^^^^

.. function:: ScanDMARC()

  Returns the `DMARC <http://wiki.halon.se/DMARC>`_ policy to apply to the message for the From-address. It will return an associative array containing the domain as result. If the domain cannot be properly extracted or missing an error message will be returned.

  :return: associative array containing the domain and result or an error.
  :rtype: array or string

  ================================== ==========
  "permerror"                        An unknown error occurred (more details may be available in the log)
  ["example.com" => "temperror"]     A temporary error occurred (but the domain was known)
  ["example.com" => "policy_absent"] No DMARC policy for domain
  ["example.com" => "none"]          The policy resulted in none
  ["example.com" => "reject"]        The policy resulted in reject
  ["example.com" => "quarantine"]    The policy resulted in quarantine
  ================================== ==========

.. function:: DKIMSign(selector, domain, key, [options])

  Sign the message using `DKIM <http://wiki.halon.se/DKIM>`_.

  :param string selector: selector to use when signing
  :param string domain: domain to use when signing
  :param string key: private key to use, either ``pki:X`` or a private RSA key in PEM format.
  :param array options: options array
  :return: true if the message could be signed
  :rtype: boolean

  The following options are available in the options array.

   * **canonicalization_header** (string) body canonicalization (``simple`` or ``relaxed``). The default is ``relaxed``.
   * **canonicalization_body** (string) body canonicalization (``simple`` or ``relaxed``). The default is ``relaxed``.
   * **algorithm** (string) algorithm to hash the message with (``sha1`` or ``sha256``). The default is ``sha256``.
   * **additional_headers** (array) additional headers to sign in addition to those recommended by the RFC.
   * **headers** (array) headers to sign. The default is to sign all headers recommended by the RFC.
   * **discard_changes** (boolean) Discard any changes to the original message before signing. The default is ``false``.
   * **return_header** (boolean) Return the DKIM signature as a string, instead of adding it to the message. The default is ``false``.

  .. note::

   If `return_header` is used, you need to add the header yourself without refolding.

	  .. code-block:: hsl

		$dkimsig = DKIMSign("selector", "example.com", $key, ["return_header" => true]);
		AddHeader("DKIM-Signature", $dkimsig, false); // without refolding

.. function:: DKIMSDID([explicitdomains, [options]])

  Returns the SDID (Signing Domain IDentifier) status from the `DKIM <http://wiki.halon.se/DKIM>`_ header of the message.

  :param array explicitdomains: array of explicit domains to check, empty array for all
  :param array options: options array
  :return: associative array containing the domain and result.
  :rtype: array

  The following options are available in the options array.

   * **signature_limit** (number) signatures to verify. The default is ``5``.

  ========= ===========
  Result    Description
  ========= ===========
  skip      The validation of the DKIM record was not checked (due to the domain filter or signature limit)
  pass      The message was signed and the signature(s) passed verification.
  fail      The message was signed but they failed the verification.
  temperror A later attempt may produce a final result.
  permerror A later attempt is unlikely to produce a final result.
  ========= ===========

.. function:: DKIMADSP()

  Returns the ADSP (Author Domain Signing Practices) policy from the `DKIM <http://wiki.halon.se/DKIM>`_ header of the message.

  :return: associative array containing the domain and result.
  :rtype: array

  ========= ===========
  Result    Description
  ========= ===========
  none      No DKIM Author Domain Signing Practices (ADSP) record was published.
  pass      This message had an Author Domain Signature that was validated. (An ADSP check is not strictly required to be performed for this result since a valid Author Domain Signature satisfies all possible ADSP policies.)
  unknown   No valid Author Domain Signature was found on the message and the published ADSP was "unknown".
  fail      No valid Author Domain Signature was found on the message and the published ADSP was "all".
  discard 	No valid Author Domain Signature was found on the message and the published ADSP was "discardable".
  nxdomain  Evaluating the ADSP for the Author's DNS domain indicated that the Author's DNS domain does not exist.
  temperror An ADSP record could not be retrieved due to some error that is likely transient in nature, such as a temporary DNS error. A later attempt may produce a final result.
  permerror An ADSP record could not be retrieved due to some error that is likely not transient in nature, such as a permanent DNS error. A later attempt is unlikely to produce a final result.
  ========= ===========

  As defined in `RFC5617 <http://tools.ietf.org/search/rfc5617>`_.

MIME and attachments
^^^^^^^^^^^^^^^^^^^^

.. function:: GetMailFile()

  Return a :class:`File` class to the current mail file.

  :return: A File class to the current mail file.
  :rtype: File

  .. note::

  	The file is returned in an unmodified state as received (only with a Received header applied).

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
								cache [ "ttl" => 3600 * 24 * 7 ]
									http("https://pbs.twimg.com/profile_images/656816032930119680/52m1eugJ.jpg")
							)
					)
				)
	);

  .. note::

    Changes done to any MIME object will **not** be reflected on consecutive calls to "get" functions, however they will be applied to the message upon delivery.

  .. function:: MIME.getID()

	  Return the MIME part's ID. This ID can be used to instantiate a new :class:`~data.MIME` object.

	  :return: part id
	  :rtype: string

  .. function:: mime.getsize()

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

	   * **index** (number) The index of the header, from the top, starting at zero.

	  .. code-block:: hsl

	    if (is_string($contentid = $part->getHeader("Content-ID")))
		  echo "Content-ID is $contentid";

      .. note::

	   The ``getHeader`` function family will return headers as a UTF-8 string with all MIME encoded-words decoded (`=?charset?encoding?data?=`). However even if headers must be in 7-bit ASCII, some senders do not conform to this and do send headers with different charset encodings. In those cases we (1) Use the MIME-parts "Content-Type" headers charset when converting to UTF-8. (2) If there is no charset information available we use a statistical charset detection function. (3) We just pretend it to be US-ASCII and covert it to UTF-8 anyway (guaranteeing the result will be valid UTF-8).

  .. function:: MIME.getHeaders(name)

	  Return a list of header values. If no header is found, an empty list is returned. The name is not case sensitive.

	  :param string name: name of the header
	  :return: header values
	  :rtype: array of string

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

  .. function:: MIME.addHeader(name, value)

	  Add a new header (at the top of the message).

	  :param string name: name of the header
	  :param string value: value of the header
	  :rtype: none

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

  .. function:: MIME.getBody()

	  Get the body (content) of a MIME part. The content will be decoded according to the `Content-Transfer-Encoding` header. If the body size is bigger than 1 MiB, the type `none` is returned.

	  :return: the body content
	  :rtype: string (or none)

      .. note::

	   The ``getBody`` function will decode using the "Content-Transfer-Encoding" header. It will not do any character set encoding, hence the data can be in any character set encoding.

  .. function:: MIME.setBody(data)

	  Set the body (content) of a MIME part. If the body argument is bigger than 1 MiB (or an another error occured), the type `none` is returned. The MIME parts encoding (`Content-Transfer-Encoding`) will be changed to `base64` as the data will encoded as such.

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

On script error
---------------

On script error ``Defer()`` is called.

On implicit termination
-----------------------

If not explicitly terminated then ``Deliver()`` is called.
