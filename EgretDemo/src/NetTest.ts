class NetTest extends egret.DisplayObjectContainer {
    public constructor() {
        super();
        this.addEventListener(egret.Event.ADDED_TO_STAGE, this.onAddToStage, this);
    }

    private urlloader:egret.URLLoader;

    private onAddToStage(event:egret.Event) {
        this.load();
        this.queryPost();
    }

    private load() {
        //egret.URLLoader 负责网络的连接状态操作， 同时负责接收网络回传的数据。
        this.urlloader = new egret.URLLoader();
        //控制是以文本 (URLLoaderDataFormat.TEXT)、原始二进制数据 (URLLoaderDataFormat.BINARY) 还是 URL 编码变量 (URLLoaderDataFormat.VARIABLES) 接收下载的数据。
        this.urlloader.dataFormat = egret.URLLoaderDataFormat.VARIABLES;
        //URLRequest 负责网络通信数据管理。
        var urlreq:egret.URLRequest = new egret.URLRequest();
        urlreq.url = "http://httpbin.org/user-agent";
        this.urlloader.load(urlreq);
        this.urlloader.addEventListener(egret.Event.COMPLETE, this.onComplete, this);
    }

    private onComplete(event:egret.Event):void {
        console.log(this.urlloader.data);
    }

    private queryPost(){
        var url:string = "http://httpbin.org/post";
        //准备连接
        var loader:egret.URLLoader = new egret.URLLoader();
        loader.dataFormat = egret.URLLoaderDataFormat.VARIABLES;
        //准备接收
        var request:egret.URLRequest = new egret.URLRequest(url);
        request.method = egret.URLRequestMethod.POST;
        //准备参数
        request.data = new egret.URLVariables("test=ok");
        //请求进行时
        loader.load(request);
        //监听请求，处理接收到的信息
        loader.addEventListener(egret.Event.COMPLETE, this.onPostComplete, this);
    }

    private onPostComplete(event:egret.Event):void {
        var loader:egret.URLLoader = <egret.URLLoader> event.target;
        var data:egret.URLVariables = loader.data;
        console.log( data.toString() );
    }
}