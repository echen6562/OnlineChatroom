<!doctype html>
<html>
  <head>
    <title>Let's Chat!</title>
    <style>
      #messages {
        width:500px;
        height: 300px;
        display: block; 
      }
      textarea{
        display: block;
        margin-left: auto;
        margin-right: auto;
      }
      .hidden {
        display: none;
      }
      #error{
        background-color: red;
        width: max-content;
        margin-left: auto;
        margin-right: auto;
      }
      #soom{
        height: 25px;
        width: auto;
        display: inline;
      }
      h1{
        display: inline;
      }
      body{
        background:lightblue;
        text-align: center;
      }
      #admin{
        position: absolute;
        top: 5px;
        right: 5px;
      }
    </style>
  </head>

  <body>
    <div>
      <img src="soom.png" id="soom">
      <h1>Welcome to Soom Browser App</h1>
    </div>

    <!-- the fetch convenience function -->
    <script src="fetch_convenience_function.js"></script>

    <div id="panel_name">
      <h2>Enter your name:</h2>
      <input type="text" id="username">
      <button id="button_savename">Save Name & Chat!</button>
    </div>

    <div id="error" class="hidden">
      Message too short!
    </div>

    <div id="panel_chat" class="hidden">
      <textarea id="messages"></textarea>
      <input type="text" id="message">
      <button id="button_sendmessage">Send Message</button>
    </div>
      <select id="room" class="hidden">
        <option value="1"> Chatroom 1 </option>
        <option value="2"> Chatroom 2 </option>
        <option value="3"> Chatroom 3 </option>
        <option value="4"> Chatroom 4 </option>
      </select>
      <br>

      <a href="admin.html" id="admin">Admin Login Page</a>

    <!-- your custom code -->
    <script>

      // get dom queries
      const panel_chat = document.getElementById('panel_chat');
      const panel_name = document.getElementById('panel_name');
      const button_savename = document.getElementById('button_savename');
      const username = document.getElementById('username');
      const messages = document.getElementById('messages');
      const message = document.getElementById('message');
      const button_sendmessage = document.getElementById('button_sendmessage');
      const error = document.getElementById('error');
      const room = document.getElementById('room');
      let hovering = false;
      let temp = messages.scrollHeight;

      let user;

      button_savename.onclick = function(event) {
        panel_chat.classList.remove('hidden');
        panel_name.classList.add('hidden');
        room.classList.remove('hidden');        
        user = username.value;
      }

      button_sendmessage.onclick = function(event) {
        if(message.value.length < 1){
          error.classList.remove('hidden');
          return;
        }
        error.classList.add('hidden');
        performFetch({
          url: 'api.php',
          method: 'GET',
          data: {
            username: user,
            message: message.value,
            room: parseInt(room.value),
            command: 'add'
          },
          success: function(data) {
            console.log("GOOD!");
            message.value = '';
          },
          error: function(data) {

          }
        })

      }

      messages.onmouseover = function(){
        hovering = true;
      }

      messages.onmouseout = function(){
        hovering = false;
      }

      getMessages();
      function getMessages() {
        performFetch({
          url: 'api.php',
          method: 'get',
          data: {
            room: parseInt(room.value),
            command: 'get'
          },
          success: function(data) {
            let goodData = JSON.parse(data);

            messages.value = '';

            for (let i = 0; i < goodData.length; i++) {
              messages.value += goodData[i] + "\n";
            }

            setTimeout( getMessages, 2000 );
          },
          error: function(data) {
            console.log("error");
          }
        })
        if(messages.scrollHeight > temp && hovering != true){
          messages.scrollTop = messages.scrollHeight;
          temp = messages.scrollHeight;
        }

      }

    </script>

  </body>

</html>
