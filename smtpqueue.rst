SMTP queue
==========

The `queued` process implements the :doc:`pre- <predelivery>` and :doc:`post-delivery <postdelivery>` contexts. These contexts operates on a message in queue. A message in queue is not directly bound to an inbound SMTP connection, hence its delivery is not done inline.

::

	   ,-------> queue
	   |           |                     <-- Pre-delivery context
	   |   delivery attempt
	   |           |                     <-- Post-delivery context
	   \__________/ \______ done

.. toctree::

	predelivery
	postdelivery