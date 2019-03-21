.. module:: firewall

Firewall
========

The firewall script acts as a firewall. It can be enabled for services sush as HTTP, FTP, SSH and SNMP.

Variables
---------------------

These are the read-only pre-defined variables available for each connection that is established.

=========== ======= =============== ===========
Variable    Type    Example         Description
=========== ======= =============== ===========
$family     string  "ipv4"          IP family of connecting client ("ipv4" or "ipv6")
$protocol   string  "tcp"           IP protocol of local server ("tcp" or "udp")
$service    string  "ssh"           Name of local service ("ssh", "snmp", "ftp" or "httpserver:X")
$senderip   string  "192.168.1.11"  IP address of connecting client
$senderport number  41666           TCP/UDP port of connecting client
$serverip   string  "10.0.0.1"      IP address of local server
$serverport number  22              TCP/UDP port of local server
=========== ======= =============== ===========

Functions
---------

.. function:: Allow()

  Allow IP connection to be established.

  :return: doesn't return, script is terminated

.. function:: Block()

  Block IP connection from being established.

  :return: doesn't return, script is terminated

On script error
---------------

On script error :func:`Block` is called.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Allow` is called.
