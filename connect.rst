.. module:: connect

CONNECT
=======

The CONNECT context acts as a SMTP firewall.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for each connection that is established.

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$messageid        string  "18c190a3-93f-47d7-bd..."  ID of the message
$senderip         string  "192.168.1.11"             IP address of the connected client
$senderport       number  41666                      TCP port of connected client
$serverip         string  "10.0.0.1"                 IP address of the mailserver
$serverport       number  25                         TCP port of the mailserver
$serverid         string  "mailserver\:1"            ID of the mailserver profile
================= ======= ========================== ===========

Functions
---------

.. function:: Accept()

  Allow IP connection to be established.

  :return: doesn't return, script is terminated

.. function:: Reject([reason])

  Reject the connection with a permanent (554) error.

  :param string reason: the reject connection
  :return: doesn't return, script is terminated

.. function:: Defer([reason])

  Defer the connection with a permanent (450) error.

  :param string reason: the defer connection
  :return: doesn't return, script is terminated

On script error
---------------

On script error ``Defer()`` is called.

On implicit termination
-----------------------

If not explicitly terminated then ``Accept()`` is called.
