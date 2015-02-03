class GraphicsTest extends egret.DisplayObjectContainer {
    /**构造函数*/
    public constructor() {
        super();
        this.addEventListener(egret.Event.ADDED_TO_STAGE,this.onAddToStage,this);
    }

    private onAddToStage(event:egret.Event) {
        this.rect();
        this.circle();
        this.line();
        this.curve();
    }

    private rect() {
        var shp:egret.Shape = new egret.Shape();
        shp.x = 20;
        shp.y = 20;
        shp.graphics.lineStyle( 10, 0x00ff00 );
        shp.graphics.beginFill(0xff0000, 1);
        shp.graphics.drawRect( 0, 0, 100, 200 );//矩形
        shp.graphics.endFill();
        this.addChild(shp);
    }

    private circle(){
        var shp:egret.Shape = new egret.Shape();
        shp.x = 120;
        shp.y = 120;
        shp.graphics.lineStyle( 10, 0x00ff00 );
        shp.graphics.beginFill(0xff0000, 1);
        shp.graphics.drawCircle( 0, 0, 50 );//圆形
        shp.graphics.endFill();
        this.addChild(shp);
    }

    private line(){
        var shp:egret.Shape = new egret.Shape();
        shp.graphics.lineStyle( 2, 0x00ff00);
        shp.graphics.moveTo( 68, 48 );
        shp.graphics.lineTo( 167, 76 );
        shp.graphics.lineTo( 221, 118 );
        shp.graphics.lineTo( 290, 162 );
        shp.graphics.lineTo( 297, 228 );
        shp.graphics.lineTo( 412, 250 );
        shp.graphics.lineTo( 443, 174 );
        shp.graphics.endFill();
        this.addChild(shp);
        //是将已经绘制的图像全部清空
        shp.graphics.clear();
    }

    private curve() {
        var shp:egret.Shape = new egret.Shape();
        shp.graphics.lineStyle( 2, 0x00ff00);
        shp.graphics.moveTo( 200, 200 );
        shp.graphics.curveTo( 300,300, 200,400);
        shp.graphics.endFill();
        this.addChild(shp);
    }
}