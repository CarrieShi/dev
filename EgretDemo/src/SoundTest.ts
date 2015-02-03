class SoundTest extends egret.DisplayObjectContainer{
    /**构造函数*/
    public constructor() {
        super();
        this.addEventListener(egret.Event.ADDED_TO_STAGE,this.onAddToStage,this);
    }

    /**加载所需资源*/
    private onAddToStage():void {
        //使用资源管理器加载资源
        RES.addEventListener(RES.ResourceEvent.GROUP_COMPLETE,this.onResourceLoadComplete,this);
        //RES.loadConfig()通常应写在整个游戏最开始初始化的地方，并且只执行一次。
        RES.loadConfig("resource/resource.json","resource/");
        RES.loadGroup("SoundTest");
    }

    public getDescription():string {
        return "Sound Test";
    }

    private sound:egret.Sound;

    public onResourceLoadComplete():void {
        this.drawStopBtn();

        this.sound = RES.getRes("backgroundMusic");
        //循环播放音频sound.play(true);
        this.sound.play();
    }

    private drawStopBtn() {
        var spr:egret.Sprite = new egret.Sprite();
        spr.graphics.beginFill( 0x00ff00 );
        spr.graphics.drawRect( 0, 0, 100, 100);
        spr.graphics.endFill();
        spr.width = 100;
        spr.height = 100;
        this.addChild(spr);
        spr.touchEnabled = true;
        spr.addEventListener(egret.TouchEvent.TOUCH_TAP, this.onTouch, this);
    }

    private onTouch(evt:egret.TouchEvent) {
        this.sound.pause();
    }
}

