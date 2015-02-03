class TweenTest extends egret.DisplayObjectContainer {
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
        RES.loadGroup("TweenTest");
    }

    public getDescription():string {
        return "这个项目展示了Tween的位移，淡入淡出，旋转，并行，串行，延迟，回调";
    }

    public onResourceLoadComplete():void {
        //var container = new egret.DisplayObjectContainer();
        //egret.MainContext.instance.stage.addChild(container);
        var texture = RES.getRes("button_png");

        var move = new egret.Bitmap();
        move.texture = texture;
        move.x = 50;
        move.y = 50;
        this.addChild(move);
        //从(50,50)移动至(250,50)，再移回来，调用输出Hello,Tween,重复前面的过程
        egret.Tween.get(move,{loop:true}).to({x:250},2000).to({x:50},2000).call(function (){
            console.log("Hello,Tween!");
        });

        var alpha = new egret.Bitmap();
        alpha.texture = texture;
        alpha.x = 250;
        alpha.y = 50;
        this.addChild(alpha);
        //alpha设置透明度，0全透明，1不透明
        egret.Tween.get(alpha,{loop:true}).to({alpha:0},1000).to({alpha:1},1000);

        var rotation = new egret.Bitmap();
        rotation.texture = texture;
        rotation.x = 150;
        rotation.y = 150;
        this.addChild(rotation);
        //rotation旋转度
        egret.Tween.get(rotation,{loop:true}).to({rotation:360},2000).wait(500);
    }
}