.. module:: data

Per recipient
=============

The per-recipient end-of-DATA context is executed once for every recipient when the message is fully received (but not yet accepted).
If multiple types of actions are performed, the response message (sent back to the client) will be chosen in the order of Reject, Defer, Quarantine, Delete, Deliver.

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

.. include:: var_transaction.rst

Arguments
^^^^^^^^^

=================== ======= ========================== ===========
Variable            Type    Example                    Description
=================== ======= ========================== ===========
$recipient          string  "test\@example.com"        E-mail address of recipient (envelope)
$recipientlocalpart string  "test"                     Local part of recipient's address (envelope)
$recipientdomain    string  "example.com"              Domain part of recipient's address (envelope)
$transportid        string  "mailtransport\:1"         ID of the transport profile to be used
$actionid           number  1                          ID; incremented per message action/recipient (Deliver, Quarantine, etc.)
=================== ======= ========================== ===========

Functions
---------

* **Actions** :func:`Deliver` :func:`Reject` :func:`Defer` :func:`Delete` :func:`Quarantine` :func:`Done`
* **MIME and attachments** :func:`GetMailFile` :class:`~data.MIME`
* **DKIM** :func:`ScanDMARC` :func:`DKIMSign` :func:`DKIMVerify` :func:`DKIMSDID`
* **Embedded content scanning** :func:`ScanDLP` :func:`ScanRPD` :func:`ScanSA` :func:`ScanKAV` :func:`ScanCLAM`
* **Miscellaneous** :func:`GetAddressList` :func:`GetMailQueueMetric` :func:`GetTLS`
* **Context** :func:`SetSender` :func:`SetRecipient` :func:`SetMailTransport` :func:`SetDelayedDeliver` :func:`SetMetaData` :func:`GetMetaData` :func:`SetSenderIP` :func:`SetSenderHELO`
* **Headers** :func:`GetHeader` :func:`GetHeaders` :func:`AddHeader` :func:`SetHeader` :func:`PrependHeader` :func:`AppendHeader` :func:`DelHeader` :func:`GetRoute` :func:`GetDSN` :func:`GetDSNHeader`


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
   * **reason** (string) the reason to report. The default is a system generated message.
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

.. function:: Done()

  Finishes the execution of the current recipient without doing an additional action. If a message is scanned without any action, it will be deferred.

  :return: doesn't return, script is terminated

.. include:: func_eod.rst

Context
^^^^^^^
Those functions update the connection and transaction data, which is used by action functions such as :func:`Deliver` and the embedded content filtering functions.

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
These functions operate on message headers, just like :class:`~data.MIME`.

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

On script error
---------------

On script error ``Defer()`` is called.

On implicit termination
-----------------------

If not explicitly terminated then ``Deliver()`` is called.
