class BitmapTest extends egret.DisplayObjectContainer {
    /**测试用的位图*/
    private logo:egret.Bitmap;

    /**构造函数*/
    public constructor() {
        super();
        this.addEventListener(egret.Event.ADDED_TO_STAGE,this.startGame,this);
    }

    /**游戏启动后，会自动执行此方法*/
    public startGame():void {
        //alert("hello!");
        this.loadResource();
    }

    /**加载所需资源*/
    private loadResource():void {
        //使用资源管理器加载资源
        RES.addEventListener(RES.ResourceEvent.GROUP_COMPLETE,this.onResourceLoadComplete,this);
        //RES.loadConfig()通常应写在整个游戏最开始初始化的地方，并且只执行一次。
        RES.loadConfig("resource/resource.json","resource/");
        RES.loadGroup("BitmapTest");
    }

    /**加载完毕后即可使用*/
    private onResourceLoadComplete(event:RES.ResourceEvent):void {
        this.logo = new egret.Bitmap();//创建位图
        this.logo.texture = RES.getRes("egretIcon");//设置纹理
        this.addChild(this.logo);//添加到显示列表
        this.startAnimation();//动画
        this.startSpriteSheet();//精灵表
        this.startScale9Grid();//九宫格
    }

    /**使用Tween来让sky位图动起来*/
    private startAnimation():void {
        //this.logo.touchEnabled = true;//可点击
        //this.logo.width = this.logo.height = 10;//设置尺寸
        //this.logo.scaleX = this.logo.scaleY = 1.5;//设置缩放
        this.logo.rotation = 45;//旋转 0:不旋转
        //this.logo.skewX = 45;//斜切
        var offsetX:number = this.logo.width/2;
        var offsetY:number = this.logo.height/2;
        this.logo.x = offsetX;
        this.logo.y = offsetY;
        this.logo.anchorX = 0.5;//设置中心点的位置，实现围绕中心旋转
        this.logo.anchorY = 0.5;//同上

        var tw = egret.Tween.get(this.logo);
        //旋转，用时500ms
        tw.to({rotation:360},500);
        //从初始点移动至(x,y),用时500ms
        tw.to({x:280,y:offsetY},500).to({x:280,y:300},500).to({x:offsetX,y:300},500).to({x:offsetX,y:offsetY},500);
        tw.call(this.startAnimation, this);
    }

    /**使用精灵表*/
    private startSpriteSheet():void {
        var bitmap = new egret.Bitmap();
        bitmap.texture = RES.getRes("icons.activity_10");//从精灵表中获取某一项
        bitmap.x = 100;
        bitmap.y = 300;
        //在游戏中制作一些不停重复排列的地图，将填充方法设置为重复排列。
        bitmap.fillMode = egret.BitmapFillMode.REPEAT;
        bitmap.width *= 2;
        bitmap.height *= 3;
        this.addChild(bitmap);
    }

    /**九宫格:防止失真*/
    private startScale9Grid():void {
        var texture = RES.getRes("button_png");
        //var scale9Grid = new egret.Rectangle(7, 7, 46, 46);
        var scale9Grid = texture.scale9grid;//从resource.json中获取九宫格参数
        var scaleBitmap = new egret.Bitmap(texture);
        scaleBitmap.scale9Grid = scale9Grid;
        scaleBitmap.width = scaleBitmap.height = 200;
        scaleBitmap.x = 50;
        scaleBitmap.y = 50;
        this.addChild(scaleBitmap);

        var scaleBitmap2 = new egret.Bitmap(texture);
        scaleBitmap2.scale9Grid = scale9Grid;
        scaleBitmap2.width = scaleBitmap.height = 100;
        scaleBitmap2.x = 300;
        scaleBitmap2.y = 50;
        this.addChild(scaleBitmap2);
    }
}