.. module:: connect

Connect
=======

This script is executed before the SMTP banner is sent.

Variables
---------

These are the pre-defined variables available.

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a1>`   array   yes       Context/hook arguments
:ref:`$connection <v_c1>`  array   yes       Connection/session bound
:ref:`$transaction <v_t1>` array   yes       Transaction bound
$context                   any     no        Connection bound user-defined (default none)
========================== ======= ========= ===========

.. _v_a1:

Arguments
+++++++++

================= ======= ========================== ===========
Array item        Type    Example                    Description
================= ======= ========================== ===========
remoteip          string  "192.168.1.11"             IP address of the connected client
remoteport        number  41666                      TCP port of connected client
localip           string  "10.0.0.1"                 IP address of the server
localport         number  25                         TCP port of the server
serverid          string  "inbound"                  ID of the server
proxyip           string  "192.168.1.1"              IP address of the proxy server (not always available)
================= ======= ========================== ===========

.. _v_t1:

Transaction
+++++++++++

========================= ======= ========================== ===========
Array item                Type    Example                    Description
========================= ======= ========================== ===========
id                        string  "18c190a3-93f-47d7-bd..."  ID of the transaction
========================= ======= ========================== ===========

Functions
---------

.. function:: Accept([options])

  Allow the connection to be established.
  Optionally change the ``remoteip`` and PTR of the accepted client connection, which is written back to the ``$connection`` variable.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **reason** (string) The greeting banner response.
   * **remoteip** (string) Change the IP address of the accepted client connection.
   * **remoteptr** (string) Set the reverse DNS pointer (PTR) for the IP address.

  .. note::

	This can be useful for eg. decoding IPv4 addresses embedded in an IPv6 address (`RFC6052 <https://tools.ietf.org/html/rfc6052>`_).

	.. code::

		$x = unpack("N*", inet_pton($arguments["remoteip"]));
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
