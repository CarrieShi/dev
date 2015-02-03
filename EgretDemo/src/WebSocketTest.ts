class WebSocketTest extends egret.DisplayObjectContainer {
    public constructor() {
        super();
        this.addEventListener(egret.Event.ADDED_TO_STAGE, this.onAddToStage, this);
    }

    private onAddToStage(event:egret.Event) {
        this.createGameScene();
    }

    private webSocket:egret.WebSocket;

    private createGameScene():void {
        //创建WebSocket对象
        this.webSocket = new egret.WebSocket();
        //添加侦听事件，监听连接服务器是否成功
        this.webSocket.addEventListener(egret.ProgressEvent.SOCKET_DATA, this.onReceiveMessage, this);
        //添加侦听事件，监听从收到服务器数据
        this.webSocket.addEventListener(egret.Event.CONNECT, this.onSocketOpen, this);
        //连接服务器，服务器需要支持WebSocket协议
        this.webSocket.connect("echo.websocket.org", 80);
    }

    private onSocketOpen():void {
        var cmd = '{"cmd":"uzwan_login","gameId":"0","from":"guzwan","userId":"3565526"}';//"Hello Egret WebSocket, Today is Tuesday";
        console.log("连接成功，发送数据：" + cmd);
        this.webSocket.writeUTF(cmd);
    }

    private onReceiveMessage(e:egret.Event):void {
        var msg = this.webSocket.readUTF();
        console.log("收到数据：" + msg);
    }
}