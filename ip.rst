IP
==

The IP context acts as a firewall.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available for each connection that is established.

=========== ======= =============== ===========
Variable    Type    Example         Description
=========== ======= =============== ===========
$family     string  "ipv4"           IP family of connecting client ("ipv4" or "ipv6")
$senderip   string  "192.168.1.11"  IP address of connecting client
$serverip   string  "10.0.0.1"      IP address of local server
$serverport number  25              TCP/UDP port of local server
$protocol   string  "tcp"           IP protocol of local server ("tcp" or "udp")
$service    string  "mailserver\:1" Name of local service
=========== ======= =============== ===========

Functions
---------

.. function:: Allow()

  Allow IP connection to be established.

  :return: doesn't return, script is terminated

.. function:: Block(message)

  Block IP connection from being established. The message is send back to the client in a TCP reply appended by CRLF (`\\r\\n`) and the connection is closed. If message is an array of strings, each item in the array is sent appended by CRLF (`\\r\\n`).

  :param message: message to send in block message
  :type message: array or string
  :return: doesn't return, script is terminated

  .. code-block:: hsl

	// Normal Quiet Block
	Block();

	// SMTP Block
	Block("421 We think this is spam. If not contact us by phone.");

	// HTTP Block
	Block([
	  "HTTP/1.0 200 OK",
	  "Content-Type: text/html",
	  "",
	  "<html>",
	  "<head>",
	  "<title>Access Denied</title>",
	  "</head>",
	  "<body>",
	  "<i>IP ($senderip) blocked</i>",
	  "</body>",
	  "</html>"
	 ]);

  .. note::

	The message should be no longer than what fits in a single IP packet (at most 1k).

On script error
---------------

On script error ``Allow()`` is called.
