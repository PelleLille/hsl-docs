.. module:: mailfrom

MAIL FROM
=========

The ``MAIL FROM`` script allows verification of the sender.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for `MAIL FROM` command.

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
$transaction      array   ["id" => "18c190a3-93f..." Contains the transaction ID
================= ======= ========================== ===========

Arguments
^^^^^^^^^

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$sender           string  "test\@example.org"        Email address of sender (envelope), lowercase
$senderlocalpart  string  "test"                     Local part of sender's address (envelope)
$senderdomain     string  "example.org"              Domain part of sender's address (envelope)
$senderparams     array   ["SIZE" => "2048", ... ]   Sender parameters to the envelope address
================= ======= ========================== ===========

Functions
---------

.. function:: Accept([options])

  Accept the `MAIL FROM` command (sender).
  Optionally change the sender accepted, which is written back to ``$transaction``.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **sender** (string or array) Set the sender email address, either as a string or a tuple with localpart and domain. The default is ``$senderlocalpart`` at ``$senderdomain``.

  .. note::

  	This function changes the sender for all recipients. To change sender per recipient use :func:`~predelivery.SetSender` in the :doc:`Pre-delivery <predelivery>` context.

.. function:: Defer([reason, [options]])

  Defer the `MAIL FROM` command (sender) with a temporary (450) error.

  :param reason: defer message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Reject([reason, [options]])

  Reject the `MAIL FROM` command (sender) with a permanent (554) error.

  :param reason: reject message with reason
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

On script error ``Defer()`` is called.

On implicit termination
-----------------------

If not explicitly terminated then ``Accept()`` is called.
