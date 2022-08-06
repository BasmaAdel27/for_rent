<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"> </script>
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

var pusher = new Pusher('dd3cfafeb7c0b16de8e9', {
  cluster: 'eu'
});


    var channel = pusher.subscribe('NewChanne2');
    channel.bind('ConfirmOwnerRequestFromAdmin', function(data) {
      alert(JSON.stringify(data));
      
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>