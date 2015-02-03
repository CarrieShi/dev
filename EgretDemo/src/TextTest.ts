class TextTest extends egret.DisplayObjectContainer {
    /**构造函数*/
    public constructor() {
        super();
        this.addEventListener(egret.Event.ADDED_TO_STAGE, this.onAddToStage, this);
    }

    private onAddToStage(event:egret.Event) {
        var shp:egret.Shape = new egret.Shape();
        shp.graphics.beginFill(0xff0000, 1);
        shp.graphics.drawRect( 0, 0, 400, 400 );//矩形
        shp.graphics.endFill();
        this.addChild(shp);

        var label:egret.TextField = new egret.TextField();
        this.addChild( label );
        label.width = 400;
        label.height = 400;
        label.textColor = 0x00ff00;
        label.fontFamily = "KaiTi";
        label.bold = true;
        label.italic = true;
        label.stroke = 2;
        label.strokeColor = 0xffa500;
        label.textAlign = egret.HorizontalAlign.CENTER;//默认LEFT RIGHT
        label.verticalAlign = egret.VerticalAlign.BOTTOM;//默认TOP MIDDLE文本居垂直居中对齐
        label.text = "这是一个文本";
        console.log( label.width + "+"+ label.height);

        var tx:egret.TextField = new egret.TextField;
        tx.width = 400;
        tx.x = 10;
        tx.y = 10;
        tx.textColor = 0;
        tx.size = 20;
        tx.fontFamily = "微软雅黑";
        tx.textAlign = egret.HorizontalAlign.CENTER;
        tx.textFlow = <Array<egret.ITextElement>>[
            {text:"妈妈再也不用担心我在",style:{"size":"12"}}
            ,{text:"Egret",style:{"textColor":0x336699,"size":"60","strokeColor":0x6699cc, "stroke":"2"}}
            ,{text:"里说一句话不能包含各种",style:{"fontFamily":"楷体"}}
            ,{text:"五",style:{"textColor": 0xff0000}}
            ,{text:"彩",style:{"textColor": 0x00ff00}}
            ,{text:"缤",style:{"textColor": 0xf000f0}}
            ,{text:"纷",style:{"textColor": 0x00ffff}}
            ,{text:"、",style:{}}
            ,{text:"大",style:{"size":"36"}}
            ,{text:"小",style:{"size":"6"}}
            ,{text:"不",style:{"size":"16"}}
            ,{text:"一",style:{"size":"24"}}
            ,{text:"、",style:{}}
            ,{text:"格",style:{"italic":"true" ,"textColor": 0x00ff00} }
            ,{text:"式",style:{"size":"16", "textColor": 0xf000f0 } }
            ,{text:"各",style:{"italic":"true", "textColor": 0xf06f00} }
            ,{text:"样",style:{"fontFamily":"楷体"}}
            ,{text:"",style:{}}
            ,{text:"的文字了！",style:{} }
        ];

        this.addChild( tx );
    }

}