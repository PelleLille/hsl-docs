.. module:: eodonce

Per message
===========

The per-message end-of-DATA script is executed once, when the message is fully received (but not yet accepted).
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
* **Queueing** :func:`Queue` :func:`History`
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

  :param reason: defer message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Reject([reason, [options]])

  Reject (550) a message. If `reason` is an array or contains `\\n` it will be split into a multiline response.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

Queueing
^^^^^^^^

.. function:: Queue(recipient, transportid, [options])

  Queue the message.

  :param recipient: the recipient email address, either as a string or a tuple with localpart and domain
  :type recipient: string or array
  :param string transportid: the transport profile ID
  :param array options: an options array
  :return: true (or none)
  :rtype: boolean or none

  The following options are available in the options array.

   * **sender** (string) The sender email address, either as a string or a tuple with localpart and domain. The default is ``$transaction["senderlocalpart"]`` at ``$transaction["senderdomain"]``.
   * **metadata** (array) Add metadata to the queued message, as a key-value pair array of strings.
   * **hold** (boolean) Put the message in the hold (inactive) queue.
   * **delay** (number) Delay the first delivery attempt, in seconds. The default is ``0``.

.. function:: History(action, recipient, [options])

  Add an entry to the history database table.
  This function is only available in the full system distribution (virtual machine) package.
  For long-term logging in high volume systems, remote logging to an external database such as Elasticsearch is recommended.

  :param string action: the logged action; either of `REJECT`, `DELETE`, `DELIVER`, `DEFER` or `ERROR`
  :param recipient: the recipient email address, either as a string or a tuple with localpart and domain
  :type recipient: string or array
  :param array options: an options array
  :return: true (or none)
  :rtype: boolean or none

  The following options are available in the options array.

   * **sender** (string) the sender email address, either as a string or a tuple with localpart and domain. The default is ``$transaction["senderlocalpart"]`` at ``$transaction["senderdomain"]``.
   * **metadata** (array) add metadata to the history entry, as a key-value pair array of strings
   * **transportid** (string) the transport profile ID
   * **reason** (string) reason message

.. include:: func_eod.rst

On script error
---------------

On script error :func:`Defer` is called.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Defer` is called.
