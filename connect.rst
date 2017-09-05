.. module:: connect

CONNECT
=======

The CONNECT context is executed before the SMTP banner is sent.

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

These are the writable pre-defined variables available.

================= ======= ===========
Variable          Type    Description
================= ======= ===========
$context          any     Connection-bound variable
================= ======= ===========

Functions
---------

.. function:: Accept()

  Allow SMTP connection to be established.

  :return: doesn't return, script is terminated

.. function:: Reject([reason, [options]])

  Reject the connection with a permanent (521) error.

  :param string reason: the reject message
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Defer the connection with a temporary (421) error.

  :param string reason: the defer message
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: SetSenderIP(senderip)

  Change the senderip for the current connection.

  :param string senderip: an IP address
  :return: senderip if successful
  :rtype: string or none
  :updates: ``$senderip``

  .. note::

	This can be useful for eg. decoding IPv4 addresses embedded in an IPv6 address (`RFC6052 <https://tools.ietf.org/html/rfc6052>`_).

	.. code::

		if ($senderip[0:9] == "64:ff9b::")
		{
			[$hi, $lo] = explode(":", $senderip[9:]);
			$padded = ("0000".$hi)[-4:].("0000".$lo)[-4:];
			SetSenderIP(implode(".", unpack("CCCC", pack("H8", $padded))));
		}

On script error
---------------

On script error ``Defer()`` is called.

On implicit termination
-----------------------

If not explicitly terminated then ``Accept()`` is called.
