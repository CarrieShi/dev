class ContainerTest extends egret.DisplayObjectContainer {
    private sprcon:egret.Sprite;
    private spr1:egret.Sprite;
    private spr2:egret.Sprite;

    public constructor() {
        super();
        this.addEventListener(egret.Event.ADDED_TO_STAGE,this.onAddToStage,this);
    }

    private onAddToStage(event:egret.Event) {
        //this.displayObj();
        //this.relativeCoordinate();
        //this.depthManager();
        this.exchangeObj();
        //this.getObj();
    }

    private displayObj() {
        //显示对象独立于显示列表

        //绘制一个Sprite,红色,设置坐标和长宽
        var spr:egret.Sprite = new egret.Sprite();
        spr.graphics.beginFill( 0x00ff00 );
        spr.graphics.drawRect(0, 0, 100, 100);
        spr.graphics.endFill();
        //该对象存在，被添加到显示列表中，在画面中显示
        //该对象拥有自己的坐标属性，旋转角度属性等。这些属性都是显示对象独立拥有的。
        //一旦该显示对象被键入到显示列表之中，Egret就会按照显示对象的状态进行显示。
        this.addChild( spr );
        //该对象存在，但已被移除显示列表，画面上不显示
        //当用户将显示对象从显示列表中移除后，这些状态依然存在。
        //将一个显示对象移除显示列表后，该对象并非在内存中被销毁。我们只是不让显示对象参与渲染而已。
        //避免Uncaught Error: [Fatal]child未被addChild到该parent:，添加if判断
        if( spr.parent ){
            this.removeChild( spr );
        }
        //该对象存在，驻于内存中
    }

    private relativeCoordinate() {
        //相对坐标系
        var sprcon1:egret.Sprite = new egret.Sprite();
        sprcon1.graphics.beginFill( 0x00ff00 );
        sprcon1.graphics.drawRect(0, 0, 100, 100);
        sprcon1.graphics.endFill();
        this.addChild( sprcon1 );
        sprcon1.x = 120;

        var sprcon2:egret.Sprite = new egret.Sprite();
        sprcon2.graphics.beginFill( 0xff0000 );
        sprcon2.graphics.drawRect(0, 0, 100, 100);
        sprcon2.graphics.endFill();
        this.addChild( sprcon2 );
        sprcon2.y = 130;

        var spr:egret.Sprite = new egret.Sprite();
        spr.graphics.beginFill( 0x0000ff );
        spr.graphics.drawRect( 0, 0, 50, 50 );
        spr.graphics.endFill();
        spr.x = 10;
        spr.y = 10;
        //同一个显示对象无论被代码加入显示列表多少次，在屏幕上只绘制一次。请分别测试一下3种情况
        this.addChild(spr);
        //sprcon1.addChild(spr);
        //sprcon2.addChild(spr);
    }

    private depthManager() {
        var sprcon:egret.Sprite = new egret.Sprite();
        this.addChild( sprcon );
        sprcon.x = 10;

        for(var i:number = 0; i<4; i++)
        {
            var spr:egret.Sprite = new egret.Sprite();
            spr.graphics.beginFill( 0xffffff * Math.random() );
            spr.graphics.drawRect( 0, 0, 100, 100);
            spr.graphics.endFill();
            spr.x = i*20;
            sprcon.addChild( spr );
        }

        var sprNew:egret.Sprite = new egret.Sprite();
        sprNew.graphics.beginFill( 0xff0000 );
        sprNew.graphics.drawRect( 0, 0, 300, 150 );
        sprNew.graphics.endFill();
        sprNew.x = 10;
        sprNew.y = 50;
        //插入
        sprcon.addChildAt( sprNew, 1 );

        //关于删除
        sprcon.removeChildAt( 2 );
        //删除全部，繁琐
        //var numChild:number = sprcon.numChildren;
        //for(var t:number = 0; t<numChild; t++)
        //{
        //    sprcon.removeChildAt( 0 );
        //}
        //删除全部，便捷
        sprcon.removeChildren();
    }

    private exchangeObj() {
        this.createObj();

        //深度互换
        //sprcon.swapChildren( this.spr1, this.spr2 );
        //sprcon.swapChildrenAt( 0, 1 );
        //重设子对象深度
        this.sprcon.setChildIndex( this.spr1, 1 );
    }

    private getObj() {
        this.createObj();
        var _spr:egret.DisplayObject = this.sprcon.getChildAt( 1 );
        _spr.alpha = 0.5;
    }

    private createObj() {
        this.sprcon = new egret.Sprite();
        this.addChild( this.sprcon );
        this.sprcon.x = 10;

        this.spr1 = new egret.Sprite();
        this.spr1.graphics.beginFill( 0xff0000 );
        this.spr1.graphics.drawRect( 0, 0, 100, 100 );
        this.spr1.graphics.endFill();
        this.spr1.x = 50;
        this.spr1.name = "sprite1";
        this.sprcon.addChild( this.spr1 );

        this.spr2= new egret.Sprite();
        this.spr2.graphics.beginFill( 0x00ff00 );
        this.spr2.graphics.drawRect( 0, 0, 100, 100 );
        this.spr2.graphics.endFill();
        this.spr2.x = 100;
        this.spr2.y = 50;
        this.spr2.name = "sprite2";
        this.sprcon.addChild( this.spr2 );
    }
}