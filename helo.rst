.. module:: helo

HELO
====

The HELO context allows verification of the `HELO` and `EHLO` domain.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for `HELO` and `EHLO` command.

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
$transaction      array   ["id" => "18c190a3-93f..." Contains the transaction ID
================= ======= ========================== ===========

Arguments
^^^^^^^^^

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$senderhelo       string  "mail.example.com"         HELO message of sender
$senderhelotype   string  "EHLO"                     HELO or EHLO command
================= ======= ========================== ===========

Functions
---------

.. function:: Accept([options])

  Accept the `HELO` or `EHLO` command.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **extensions** (array) SMTP service extensions to announce.

.. function:: Reject([reason, [options]])

  Reject the `HELO` or `EHLO` command with a permanent (554) error.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Defer the `HELO` or `EHLO` command with a temporary (450) error.

  :param reason: defer message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: SetHELO(senderhelo)

  Change the HELO hostname for the current connection.

  :param string senderhelo: an hostname
  :return: senderhelo if successful
  :rtype: string or none
  :updates: ``$senderhelo``

.. function:: GetExtensions()

  Return the current SMTP service extensions to be sent if the EHLO command was issued.

  :return: the current SMTP service extensions
  :rtype: array

.. include:: func_gettls.rst

On script error
---------------

On script error ``Defer()`` is called.

On implicit termination
-----------------------

If not explicitly terminated then ``Accept()`` is called.
