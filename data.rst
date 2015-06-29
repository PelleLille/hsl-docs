.. module:: data

DATA
====

The DATA context scans the message.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for each recipient (on a message).

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$senderip         string  "192.168.1.11"             IP address of the connected client
$saslauthed       boolean true                       Whether or not the SMTP session is authenticated (SASL)
$saslusername     string  "mailuser"                 SASL username
$senderhelo       string  "mail.example.com"         HELO message of sender
$sender           string  "test\@example.org"        E-mail address of sender
$senderdomain     string  "example.org"              Domain part of sender's address
$recipient        string  "test\@example.com"        E-mail address of recipient
$recipients       array   ["test\@example.com", ...] List of all recipient addresses, in order of scanning
$recipientdomain  string  "example.com"              Domain part of recipient's address
$recipientdomains array   ["example.com", ...]       List of all domain part of all recipient addresses
$messageid        string  "18c190a3-93f-47d7-bd..."  ID of the message
$actionid         number  1                          ID; incremented per message action/recipient (Deliver, CopyMail, Quarantine, etc.)
$serverid         string  "mailserver\:1"            ID of the mailserver profile
$transportid      string  "mailtransport\:1"         ID of the transport profile to be used
================= ======= ========================== ===========

Functions
---------

* **Misc** :func:`GetAddressList` :func:`GetMailQueueMetric`
* **Routing** :func:`SetSender` :func:`SetRecipient` :func:`SetMailTransport` :func:`SetDelayedDeliver` :func:`SetMetaData`
* **Headers** :func:`GetHeader` :func:`GetHeaders` :func:`AddHeader` :func:`SetHeader` :func:`PrependHeader` :func:`AppendHeader` :func:`DelHeader` :func:`GetRoute` :func:`GetDSN` :func:`GetDSNHeader`
* **Attachments** :func:`GetAttachmentsByName` :func:`GetAttachmentsByType` :func:`GetAttachmentName` :func:`GetAttachmentType` :func:`GetAttachmentSize` :func:`GuessAttachmentType` :func:`RemoveAttachments`
* **Actions** :func:`Deliver` :func:`DirectDeliver` :func:`Reject` :func:`Defer` :func:`Delete` :func:`Quarantine` :func:`CopyMail` :func:`DiscardMailDataChanges` :func:`Done`
* **Anti-spam and anti-virus** :func:`ScanRPD` :func:`ScanRPDAV` :func:`ScanSA` :func:`ScanKAV` :func:`ScanCLAM` :func:`ScanDLP`
* **DKIM** :func:`DeliverWithDKIM` :func:`ScanDMARC` :func:`DKIMSDID` :func:`DKIMADSP`

Misc
^^^^

.. function:: GetAddressList(value)

 Extract addresses from a header value, often used with `From`, `To` and `CC` headers.

 :param string value: value to extract e-mail addresses from
 :return: e-addresses
 :rtype: array

 .. code-block:: hsl

 	$headerSender = GetAddressList(GetHeader("From"))[0]; // first e-mail address in From header

.. function:: GetMailQueueMetric(options)

  Return metric information about the mail queue, it can be used to enforce quotas.

  :param array options: options array

.. include:: func_getmailqueuemetric.rst

Routing
^^^^^^^

.. function:: SetSender(sender)

  Change the sender of the message.

  :param string sender: an e-mail address
  :rtype: none
  :updates: ``$sender`` and ``$senderdomain``

  .. warning::

  	This function changes the sender for all recipients. To change sender per recipient use :func:`~predelivery.SetSender` in the :doc:`Pre-delivery <predelivery>` context.

.. function:: SetRecipient(recipient)

  Changes the recipient.

  :param string recipient: an e-mail address
  :rtype: none
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

.. function:: SetMetaData(array)

  Set the metadata for the next recipient(s). The metadata must be an array with both string keys and values.

  :param array metadata: metadata to set
  :rtype: none

  .. code-block:: hsl

  	SetMetaData(["foo"=>"bar", "foo2" => json_encode(["array", 123.45, false])); 

  .. note::

    To work-around the data type limitation of the metadata; data can be encoded using :func:`json_encode`.

Headers
^^^^^^^

.. function:: GetHeader(name, [decode = true])

  Return the value of a header (if multiple headers with the same name exists, the first will be returned).

  :param string name: name of the header
  :param boolean decode: if false, the header will not be decoded
  :return: header value
  :rtype: string

.. function:: GetHeaders(name, [decode = true])

  Return the value of all headers with the name. If name is true, all headers will be returned.

  :param string name: name of the header
  :param boolean decode: if false, the header will not be decoded
  :return: headers' values
  :rtype: array

.. function:: AddHeader(name, value, [refold = true])

  Add a new header (at the top of the message).

  :param string name: name of the header
  :param string value: value of the header
  :rtype: none

.. function:: SetHeader(name, value, [refold = true])

  Overwrite existing header(s) or create a new header.

  :param string name: name of the header
  :param string value: value of the header
  :param boolean refold: refold header to 80 characters per line
  :return: number of headers changed
  :rtype: number

.. function:: PrependHeader(name, value, [refold = true])

  Prepend to existing header(s) or create a new header.

  :param string name: name of the header
  :param string value: value of the header
  :param boolean refold: refold header to 80 characters per line
  :return: number of headers changed
  :rtype: number

.. function:: AppendHeader(name, value, [refold = true])

  Append to existing header(s) or create a new header.

  :param string name: name of the header
  :param string value: value of the header
  :param boolean refold: refold header to 80 characters per line
  :return: number of headers changed
  :rtype: number

.. function:: DelHeader(name)

  Delete all headers by the name.

  :param string name: name of the header
  :return: number of headers deleted
  :rtype: number

.. function:: GetRoute([extended = false])

  :param boolean extended: include more information
  :return: the message's `Received` header(s) parsed in a usable format
  :rtype: array

.. function:: GetDSN()

  Parse a DSN message.

  :return: information about a DSN message
  :rtype: array or false

.. function:: GetDSNHeader(name, [skip_decode = false])

  Same as GetHeader except it works on attached DSN messages.

  :param string name: the header
  :param boolean skip_decode: if decoding should be skipped (return raw)
  :return: the header value
  :rtype: string

Attachments
^^^^^^^^^^^

.. function:: GetAttachmentsByName(filename)

  :param string filename: filename (may be regular expression)
  :return: attachment id(s)
  :rtype: array

.. function:: GetAttachmentsByType(mimetype)

  :param string mimetype: mimetype (may be regular expression)
  :return: attachment id(s)
  :rtype: array

.. function:: GetAttachmentName(attachmentid)

  :param attachmentid: attachment id(s)
  :type attachmentid: array or string
  :return: name of attachment(s)
  :rtype: array

.. function:: GetAttachmentType(attachmentid)

  :param attachmentid: attachment id(s)
  :type attachmentid: array or string
  :return: mimetype of attachment(s)
  :rtype: array

.. function:: GetAttachmentSize(attachmentid)

  :param attachmentid: attachment id(s)
  :type attachmentid: array or string
  :return: size of attachment(s)
  :rtype: array

  .. note::
  	
	If attachmentid is "/" the message size is returned.

.. function:: GuessAttachmentType(attachmentids)

  Guess the attachment type based on file magic.

  :param attachmentid: attachment id(s)
  :type attachmentid: array or string
  :return: mimetype of attachment(s)
  :rtype: array

.. function:: RemoveAttachments(attachmentids)

  Remove attachmentid(s).

  :param attachmentid: attachment id(s)
  :type attachmentid: array or string
  :rtype: none

Actions
^^^^^^^

.. function:: Deliver([recipient, [transportid]])

  Deliver the message.

  :param string recipient: an e-mail address
  :param string transportid: the transportid to be used
  :return: doesn't return, script is terminated
  :updates: ``$actionid``

.. function:: DirectDeliver([recipient, [transportid]])

  Deliver the message inline.

  :param string recipient: an e-mail address
  :param string transportid: the transportid to be used
  :return: doesn't return, script is terminated
  :updates: ``$actionid``

.. function:: Reject([reason])

  Reject (550) a message. If `reason` is an array or contains `\\n` it will be split into a multiline response.

  :param reason: reject message with reason
  :type reason: string or array
  :return: doesn't return, script is terminated
  :updates: ``$actionid``

.. function:: Defer([reason])

  Defer (421) a message. If `reason` is an array or contains `\\n` it will be split into a multiline response.

  :param reason: reject message with reason
  :type reason: string or array
  :return: doesn't return, script is terminated
  :updates: ``$actionid``

.. function:: Delete()

  Delete the message (and return 250).

  :return: doesn't return, script is terminated
  :updates: ``$actionid``

.. function:: Quarantine(quarantineid, [recipient, [transportid, [options]]])

  Quarantine or `archive <http://wiki.halon.se/Archiving>`_ a message.

  :param string quarantineid: the quarantine profile
  :param string recipient: an e-mail address
  :param string transportid: the transportid to be used
  :param array options: an options array
  :return: doesn't return, script is terminated
  :updates: ``$actionid``

  The following options are available in the options array.

   * **final_action** (boolean) if the function should terminate the script. The default is ``true``.
   * **reject** (boolean) if the function should return an 500 error. The default is ``true``.
   * **reason** (string) the reason to report. The default is a system generated message.

.. function:: CopyMail([recipient, [transportid]])

  :param string recipient: an e-mail address
  :param string transportid: the transportid to be used
  :rtype: none
  :updates: ``$actionid``

.. function:: DiscardMailDataChanges()

  Discard any content changes to the message.

  :return: number of changes discarded
  :rtype: number

.. function:: Done()

  Finishes the execution of the current recipient without doing an additional action. This can be used with e.g. :func:`CopyMail`. If a message is scanned without any action, it will be deferred.

  :return: doesn't return, script is terminated

Anti-spam and anti-virus
^^^^^^^^^^^^^^^^^^^^^^^^

.. function:: ScanRPD([options])

  Scan the message using `CYREN <http://wiki.halon.se/CYREN>`_ RPD.

  :param array options: options array
  :return: score or refid
  :rtype: number or string

  The following options are available in the options array.

   * **refid** (boolean) Return RefID (used to report FN and FP). The default is ``false``.

  ===== ===========
  Score Description
  ===== ===========
  0     Unknown
  10    Suspect
  40    Valid bulk
  50    Bulk 
  100   Spam
  ===== ===========

.. function:: ScanRPDAV()

  Scan the message using `CYREN <http://wiki.halon.se/CYREN>`_ RPD's outbreak anti-virus.

  :return: score
  :rtype: number

  ===== ===========
  Score Description
  ===== ===========
  0     Unknown
  50    Medium probability
  100   High probability 
  ===== ===========

.. function:: ScanSA([options])

  Scan the message using `SpamAssassin <http://wiki.halon.se/SpamAssassin>`_.

  :param array options: options array
  :return: score or rules
  :rtype: number or array

  The following options are available in the options array.

   * **rules** (boolean) Return rules in an associative array with scores. The default is ``false``.

  ========================== ===== ===========
  Builtin rules              Score Description
  ========================== ===== ===========
  NOT_SCANNED_TOO_BIG        0     Message was to big too big to be scanned
  NOT_SCANNED_QUEUE_TOO_LONG 0     Queue was too long to SpamAssassin
  ========================== ===== ===========

  A score of `5` or higher is what most people accept to be considered spam.

.. function:: ScanKAV()

  Scan the message using Kaspersky anti-virus.

  :return: any viruses found
  :rtype: array

.. function:: ScanCLAM()

  Scan the message using CLAM anti-virus.

  :return: any viruses found
  :rtype: array

.. function:: ScanDLP([patterns, [options]])

  Scan a message using the builtin `DLP <http://wiki.halon.se/DLP>`_ engine.

  :param array patterns: array of specific rules to look for
  :param array options: options array
  :return: all patterns found (may include `ERR_` rules even if not explicitly given in the `patterns` argument)
  :rtype: array

  The following options are available in the options array.

   * **stop_on_match** (boolean) processing the mail when one match (of the requested type) is found. The default is ``false``.
   * **timeout** (number) set an approximate timeout time in seconds. The default in no timeout.
   * **recursion_limit** (number) how deep to dig through MIME trees, archive files (such as ZIP), etc. The default is ``9``.

  ========================== ===========
  Builtin rules              Description
  ========================== ===========
  ERR_UNKNOWN_ERROR          An unknown error occurred (more details may be available in the log)
  ERR_PASSWORD_PROTECTED     The archive is password protected
  ERR_RECURSION_LIMIT        The archive is too nested
  ========================== ===========

DKIM
^^^^

.. function:: DeliverWithDKIM(selector, domain, key, [options])

  Sign and deliver the message using `DKIM <http://wiki.halon.se/DKIM>`_.

  :param string selector: selector to use when signing
  :param string domain: domain to use when signing
  :param string key: private key to use, either ``pki:X`` or a private RSA key in PEM format.
  :param array options: options array
  :return: doesn't return, script is terminated
  :updates: ``$transportid``

  The following options are available in the options array.

   * **canonicalization_header** (string) body canonicalization (``simple`` or ``relaxed``). The default is ``simple``.
   * **canonicalization_body** (string) body canonicalization (``simple`` or ``relaxed``). The default is ``simple``.
   * **algorithm** (string) algorithm to hash the message with (``sha1`` or ``sha256``). The default is ``sha256``.
   * **additional_headers** (array) additional headers to sign in addition to those recommended by the RFC.
   * **headers** (array)  headers to sign. The default is to sign all headers recommended by the RFC.
   * **discard_changes** (boolean) Discard any changes to the original message before signing. The default is ``false``.

.. function:: ScanDMARC()

  Returns the `DMARC <http://wiki.halon.se/DMARC>`_ policy to apply to the message for the From-address. It will return an associative array containing the domain as result. If the domain cannot be properly extracted or missing an error message will be returned. 

  :return: associative array containing the domain and result or an error. 
  :rtype: array or string

.. function:: DKIMSDID([explicitdomains, [options]])

  Returns the SDID (Signing Domain IDentifier) status from the `DKIM <http://wiki.halon.se/DKIM>`_ header of the message.

  :param array explicitdomains: array of explicit domains to check, empty array for all
  :param array options: options array
  :return: associative array containing the domain and result. 
  :rtype: array

  The following options are available in the options array.

   * **signature_limit** (number) signatures to verify. The default is ``5``.

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

On script error
---------------

On script error ``Defer()`` is called.
