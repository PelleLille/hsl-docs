.. module:: mailfrom

MAIL FROM
=========

The MAIL FROM context allows verification of the `MAIL FROM` sender.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for `MAIL FROM` command.

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$messageid        string  "18c190a3-93f-47d7-bd..."  ID of the message
$senderip         string  "192.168.1.11"             IP address of the connected client
$senderport       number  41666                      TCP port of connected client
$serverip         string  "10.0.0.1"                 IP address of the mailserver
$serverport       number  25                         TCP port of the mailserver
$serverid         string  "mailserver\:1"            ID of the mailserver profile
$senderhelo       string  "mail.example.com"         HELO message of sender
$tlsstarted       boolean false                      Whether or not the SMTP session is using TLS
$saslusername     string  "mailuser"                 SASL username
$saslauthed       boolean true                       Whether or not the SMTP session is authenticated (SASL)
$senderdomain     string  "example.org"              Domain part of sender's address (envelope)
$sender           string  "test\@example.org"        E-mail address of sender (envelope)
$senderparams     array   ["SIZE" => "2048", ... ]   Sender parameters to the envelope address
================= ======= ========================== ===========

Functions
---------

.. function:: Accept()

  Accept the `MAIL FROM` command (sender).

  :return: doesn't return, script is terminated

.. function:: Reject([reason, [options]])

  Reject the `MAIL FROM` command (sender) with a permanent (554) error.

  :param string reason: the reject message
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) disconnect the client. The default is ``false``.

.. function:: Defer([reason, [options]])

  Defer the `MAIL FROM` command (sender) with a temporary (450) error.

  :param string reason: the defer message
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) disconnect the client. The default is ``false``.

.. function:: SetSender(sender)

  Change the sender of the message.

  :param string sender: an e-mail address
  :rtype: none
  :updates: ``$sender`` and ``$senderdomain``

  .. warning::

  	This function changes the sender for all recipients. To change sender per recipient use :func:`~predelivery.SetSender` in the :doc:`Pre-delivery <predelivery>` context.

.. function:: GetMailQueueMetric(options)

  Return metric information about the mail queue, it can be used to enforce quotas.

  :param array options: options array

.. include:: func_getmailqueuemetric.rst

On script error
---------------

On script error ``Defer()`` is called.

On implicit termination
-----------------------

If not explicitly terminated then ``Reject()`` is called (if not $error is set, then ``Defer()`` is called instead).
