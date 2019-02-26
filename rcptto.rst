.. module:: rcptto

RCPT TO
=======

The ``RCPT TO`` script allows verification of recipients.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for `RCPT TO` command.

Connection
^^^^^^^^^^

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$senderip         string  "192.168.1.11"             IP address of the connected client
$senderport       number  41666                      TCP port of connected client
$serverip         string  "10.0.0.1"                 IP address of the server
$serverport       number  25                         TCP port of the server
$serverid         string  "mailserver\:1"            ID of the server
$senderhelo       string  "mail.example.com"         HELO hostname of sender
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
$recipient          string  "test\@example.com"        Email address of recipient (envelope), lowercase
$recipientlocalpart string  "test"                     Local part of recipient's address (envelope)
$recipientdomain    string  "example.com"              Domain part of recipient's address (envelope)
$recipientparams    array   ["NOTIFY" => "NEVER", .. ] Recipient parameters to the envelope address
$transportid        string  "mailtransport\:1"         ID of the transport profile to be used
=================== ======= ========================== ===========

Functions
---------

.. function:: Accept([options])

  Accept the `RCPT TO` command (recipient).
  Optionally change the recipient accepted and its transport, which is written back to ``$transaction`` for subsequent executions.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **recipient** (string or array) Set the recipient email address, either as a string or a tuple with localpart and domain. The default is ``$recipientlocalpart`` at ``$recipientdomain``.
   * **transportid** (string) Set the transport ID. The default is ``$transportid``.
   * **reason** (string) The reason to report. The default is a system generated message.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Reject([reason, [options]])

  Reject the `RCPT TO` command (recipient) with a permanent (554) error.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Defer the `RCPT TO` command (recipient) with a temporary (450) error.

  :param reason: defer message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: GetMailQueueMetric([options])

  Return metric information about the mail queue, it can be used to enforce quotas.

  :param array options: options array
  :rtype: number

.. include:: func_getmailqueuemetric.rst

.. include:: func_gettls.rst

On script error
---------------

On script error :func:`Defer` is called.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Accept` is called.
