.. function:: PickupSuspend(fields, ttl, [options])

  Add a dynamic queue pickup suspend for the following fields, this will cause no more messages matching this condition to be picked up for delivery during the ttl of the suspend. If a matching suspend already exists only the TTL is updated.

  :param array fields: fields array
  :param number ttl: ttl in seconds
  :param array options: options array
  :rtype: boolean

  The following fields are available in the fields array.

   * **transportid** (string) Transport ID
   * **localip** (string) Local IP
   * **remoteip** (string) Remote IP
   * **remotemx** (string) Remote MX
   * **recipientdomain** (string) Recipient domain
   * **jobid** (string) Job ID

  The following options are available in the options array.

   * **tag** (string) Apply tag to suspend.
   * **update** (boolean) If we should update the suspend. The default is ``true``.

  .. code-block:: hsl

     // Suspend matching traffic for one minute
     PickupSuspend(
            ["remotemx" => "*.protection.example.com"],
            60
         );

.. function:: PickupPolicy(fields, condition, policy, ttl, [options])

  Add a dynamic queue pickup policy for the specific fields, matching a specific condition. If a matching policy (fields and condtion) already exists the condition and TTL is updated.

  :param array fields: fields array
  :param array condition: condition array
  :param array policy: policy array
  :param number ttl: ttl in seconds
  :param array options: options array
  :rtype: boolean

  The following field values are available in the fields array. The counter for those fields needs to exists in the policy configuration before it can have dynamic condition added to it.

   * ``transportid``
   * ``localip``
   * ``remoteip``
   * ``remotemx``
   * ``recipientdomain``
   * ``jobid``

  The following condition are available in the condition array.

   * **transportid** (string) Transport ID
   * **localip** (string) Local IP
   * **remoteip** (string) Remote IP
   * **remotemx** (string) Remote MX
   * **recipientdomain** (string) Recipient domain
   * **jobid** (string) Job ID

  The following policies are available in the policy array.

   * **concurrency** (number) The concurrency limit.
   * **rate** (array) The rate given as [messages, interval]. The interval is given in seconds.
   * **tag** (string) Tag if concurrency or rate applies.

  The following options are available in the options array.

  * **update** (boolean) If we should update the policy. The default is ``true``.

  .. code-block:: hsl

     // Enforce a 10 messages per minute limit for an hour
     PickupPolicy(
           ["localip", "recipientdomain"],
           ["recipientdomain" => "example.com"],
           ["rate" => [10, 60]],
           3600
         );

  .. note::

	  If you have groupings and you add a condition matching a grouping, then the condition will be applied on the grouping and not the individual item. For example if you have a grouping named ``#exampleRollup`` (*.example.com) and you add a condition for ``mx1.example.com`` the condition will be applied on the ``#exampleRollup`` grouping instead.