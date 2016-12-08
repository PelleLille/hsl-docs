.. module:: rcptto

RCPT TO
=======

The RCPT TO context allows verification of `RCPT TO` recipients.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for `RCPT TO` command.

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
$tlsprotocol      string  "TLSv1.2"                  Negotiated TLS version
$tlscipher        string  "DHE-RSA-AES256..."        Negotiated TLS cipher
$tlskeysize       number  256                        Negotiated TLS key size
$saslusername     string  "mailuser"                 SASL username
$saslauthed       boolean true                       Whether or not the SMTP session is authenticated (SASL)
$senderdomain     string  "example.org"              Domain part of sender's address (envelope)
$sender           string  "test\@example.org"        E-mail address of sender (envelope)
$recipientdomain  string  "example.com"              Domain part of recipient's address (envelope)
$recipient        string  "test\@example.com"        E-mail address of recipient (envelope)
$recipientparams  array   ["SIZE" => "2048", ... ]   Recipient parameters to the envelope address
$recipientdomains array   ["example.com", ...]       List of all domain part of all recipient addresses (envelope)
$recipients       array   ["test\@example.com", ...] List of all recipient addresses (envelope), in order of scanning
$transportid      string  "mailtransport\:1"         ID of the transport profile to be used
================= ======= ========================== ===========

Functions
---------

.. function:: Accept()

  Accept the `RCPT TO` command (recipient).

  :return: doesn't return, script is terminated

.. function:: Reject([reason])

  Reject the `RCPT TO` command (recipient) with a permanent (554) error.

  :param string reason: the reject message
  :return: doesn't return, script is terminated

.. function:: Defer([reason])

  Defer the `RCPT TO` command (recipient) with a temporary (450) error.

  :param string reason: the defer message
  :return: doesn't return, script is terminated

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
