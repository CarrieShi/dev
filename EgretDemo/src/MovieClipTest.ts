class MovieClipTest extends egret.DisplayObjectContainer {
    /**构造函数*/
    public constructor() {
        super();
        this.addEventListener(egret.Event.ADDED_TO_STAGE,this.startGame,this);
    }

    /**游戏启动后，会自动执行此方法*/
    public startGame():void {
        this.loadResource();
    }

    /**加载所需资源*/
    private loadResource():void {
        //使用资源管理器加载资源
        RES.addEventListener(RES.ResourceEvent.GROUP_COMPLETE,this.onResourceLoadComplete,this);
        //RES.loadConfig()通常应写在整个游戏最开始初始化的地方，并且只执行一次。
        RES.loadConfig("resource/resource.json","resource/");
        RES.loadGroup("MovieClipTest");
    }

    public getDescription():string {
        return "MovieClip";
    }

    public onResourceLoadComplete():void {
        var data = RES.getRes("chunli_json");
        var texture = RES.getRes("chunli_png");
        var mcDataFactory = new egret.MovieClipDataFactory(data, texture);
        var chunli = new egret.MovieClip(mcDataFactory.generateMovieClipData("test"));
        chunli.x = 100;
        chunli.y = 220;
        chunli.scaleY = 1.5;
        egret.MainContext.instance.stage.addChild(chunli);
        chunli.gotoAndPlay("attack", -1);
    }
}