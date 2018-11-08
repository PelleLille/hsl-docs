.. module:: eodonce

Per message
===========

The per-message end-of-DATA context is executed once on EOD (the dot ``.``), when the message is fully received (but not yet accepted).
To relay the message for all recipients, call :func:`Queue` for each ``$transaction["recipients"]`` and then :func:`Accept`.

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
The are no arguments to the per-message end-of-DATA script. The mail data file is however available via several functions.

Functions
---------

* **Actions** :func:`Accept` :func:`Defer` :func:`Reject`
* **Queueing** :func:`Queue`
* **MIME and attachments** :func:`GetMailFile` :class:`~data.MIME`
* **DKIM** :func:`ScanDMARC` :func:`DKIMSign` :func:`DKIMVerify` :func:`DKIMSDID`
* **Embedded content scanning** :func:`ScanDLP` :func:`ScanRPD` :func:`ScanSA` :func:`ScanKAV` :func:`ScanCLAM`
* **Miscellaneous** :func:`GetAddressList` :func:`GetMailQueueMetric` :func:`GetTLS`

Actions
^^^^^^^

.. function:: Accept()

  Accept the `DATA` command (mail data).

  :return: doesn't return, script is terminated

.. function:: Defer([reason, [options]])

  Defer (421) a message. If `reason` is an array or contains `\\n` it will be split into a multiline response.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Reject([reason, [options]])

  Reject (550) a message. If `reason` is an array or contains `\\n` it will be split into a multiline response.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

Queueing
^^^^^^^^

.. function:: Queue(recipient, transportid, [options])

  Queue the message.

  :param string recipient: the recipient address
  :param string transportid: the transport profile ID
  :param array options: an options array
  :return: true (or none)
  :rtype: boolean or none

  The following options are available in the options array.

   * **sender** (string) Set the sender address. The default is ``$transaction["sender"]``.
   * **metadata** (array) Add metadata to the message (KVP).

.. include:: func_eod.rst

On script error
---------------

On script error :func:`Defer` is called.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Defer` is called.
