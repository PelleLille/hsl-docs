.. module:: connect

Connect
=======

This script is executed before the SMTP banner is sent.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for each connection that is established.

Connection
^^^^^^^^^^

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
$senderip         string  "192.168.1.11"             IP address of the connected client
$senderport       number  41666                      TCP port of connected client
$serverip         string  "10.0.0.1"                 IP address of the server
$serverport       number  25                         TCP port of the server
$serverid         string  "mailserver\:1"            ID of the server
================= ======= ========================== ===========

Functions
---------

.. function:: Accept([options])

  Allow the connection to be established.
  Optionally change the ``$senderip`` and PTR of the accepted client connection, which is written back to the connection context.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **reason** (string) The HELO banner response.
   * **senderip** (string) Set the IP address of the accepted client connection. The default is ``$senderip``.
   * **senderptr** (string) Set the reverse DNS pointer (PTR) for the IP address.

  .. note::

	This can be useful for eg. decoding IPv4 addresses embedded in an IPv6 address (`RFC6052 <https://tools.ietf.org/html/rfc6052>`_).

	.. code::

		$x = unpack("N*", inet_pton($senderip));
		if (count($x) == 4 and $x[0:3] == [6619035, 0, 0]) // 64:ff9b::[IPv4]
			$ip = inet_ntop(pack("N", $x[3]));

.. function:: Reject([reason, [options]])

  Close the connection with a permanent (521) error.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Close the connection with a temporary (421) error.

  :param reason: defer message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

On script error
---------------

On script error :func:`Defer` is called.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Accept` is called.
