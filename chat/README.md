# Chat

This folder contains sourcecode of chat gateway.

The reason its separated is becasue of how does Lemon work and thus doesn't support websockets. This way its written as second process which handles the messages.

Based around workerman.

## z komunikation prokotol

comunication via JSON format bcs NO WE ARE NOT WRITING BINARY PROTOCOL TODAY OKAY TIME IS MONEY AND WE DONT HAVE BOTH SO

in:

```json
auth
{
    "type": 0,
    "session": "PHP session token",
    "reservation": "ID of reservation to which is this conversation asociated",
}

message
{
    "type": 1,
    "content": "The content of the message",
}
```


The gateway will just provide realtime comunication without pooling or stuff. Auth and storing in db still handles our first app, it is basicaly the same amount of requests we did earlier just in real time
