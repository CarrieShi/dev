/**
 * Copyright (c) 2014,Egret-Labs.org
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Egret-Labs.org nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY EGRET-LABS.ORG AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL EGRET-LABS.ORG AND CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

class Main extends egret.DisplayObjectContainer{

    /**
     * 加载进度界面
     */
    private loadingView:LoadingUI;

    public constructor() {
        super();
        this.addEventListener(egret.Event.ADDED_TO_STAGE,this.onAddToStage,this);
    }

    private onAddToStage(event:egret.Event){
        //注入自定义的素材解析器*****************GUI
        egret.Injector.mapClass("egret.gui.IAssetAdapter",AssetAdapter);
        //加载皮肤主题配置文件,可以手动修改这个文件。替换默认皮肤。***************GUI
        egret.gui.Theme.load("resource/theme.thm");
        //设置加载进度界面
        this.loadingView  = new LoadingUI();
        this.stage.addChild(this.loadingView);

        //初始化Resource资源加载库
        RES.addEventListener(RES.ResourceEvent.CONFIG_COMPLETE,this.onConfigComplete,this);
        RES.loadConfig("resource/resource.json","resource/");
        //初始化themeResource资源加载库***************GUI
        RES.loadConfig("resource/themeResource.json","resource/");
    }
    /**
     * 配置文件加载完成,开始预加载preload资源组。
     */
    private onConfigComplete(event:RES.ResourceEvent):void{
        RES.removeEventListener(RES.ResourceEvent.CONFIG_COMPLETE,this.onConfigComplete,this);
        RES.addEventListener(RES.ResourceEvent.GROUP_COMPLETE,this.onResourceLoadComplete,this);
        RES.addEventListener(RES.ResourceEvent.GROUP_PROGRESS,this.onResourceProgress,this);
        //若同时启动多个资源组一起加载，比如在加载"preload"前，先加载一个更小的"loading"资源组，以提供显示"preload"组加载进度的素材
        //RES.loadGroup("loading",1);
        //RES.loadGroup("preload",0);
        RES.loadGroup("preload");
    }
    /**
     * preload资源组加载完成
     */
    private onResourceLoadComplete(event:RES.ResourceEvent):void {
        if(event.groupName=="preload"){
            this.stage.removeChild(this.loadingView);
            RES.removeEventListener(RES.ResourceEvent.GROUP_COMPLETE,this.onResourceLoadComplete,this);
            RES.removeEventListener(RES.ResourceEvent.GROUP_PROGRESS,this.onResourceProgress,this);
            this.createGameScene();
        }
    }
    /**
     * preload资源组加载进度
     */
    private onResourceProgress(event:RES.ResourceEvent):void {
        //组加载事件回调函数里的写法，需要使用event.groupName判断下这个事件是属于哪个资源组，因为可能有多个资源组同时在加载。
        if(event.groupName=="preload"){
            this.loadingView.setProgress(event.itemsLoaded,event.itemsTotal);
        }
    }

    private textContainer:egret.Sprite;

    private gameLayer:egret.DisplayObjectContainer;//**********GUI

    private guiLayer:egret.gui.UIStage;//**********GUI
    /**
     * 创建游戏场景
     */
    private createGameScene():void{

        //var sky:egret.Bitmap = this.createBitmapByName("bgImage");
        //this.addChild(sky);
        //var stageW:number = this.stage.stageWidth;
        //var stageH:number = this.stage.stageHeight;
        //sky.width = stageW;
        //sky.height = stageH;
        //
        //var topMask:egret.Shape = new egret.Shape();
        //topMask.graphics.beginFill(0x000000, 0.5);
        //topMask.graphics.drawRect(0, 0, stageW, stageH);
        //topMask.graphics.endFill();
        //topMask.width = stageW;
        //topMask.height = stageH;
        //this.addChild(topMask);
        //
        //// this doesn't work
        //var icon:egret.Bitmap = this.createBitmapByName("egretIcon");
        //icon.anchorX = icon.anchorY = 0.5;
        //this.addChild(icon);
        //icon.x = stageW / 2;
        //icon.y = stageH / 2 - 60;
        //icon.scaleX = 0.55;
        //icon.scaleY = 0.55;
        //
        //var colorLabel:egret.TextField = new egret.TextField();
        //colorLabel.x = stageW / 2;
        //colorLabel.y = stageH / 2 + 50;
        //colorLabel.anchorX = colorLabel.anchorY = 0.5;
        //colorLabel.textColor = 0xffffff;
        //colorLabel.textAlign = "center";
        //colorLabel.text = "Hello Egret";
        //colorLabel.size = 20;
        //this.addChild(colorLabel);
        //
        ////this doesn't work
        //var textContainer:egret.Sprite = new egret.Sprite();
        //textContainer.anchorX = textContainer.anchorY = 0.5;
        //this.addChild(textContainer);
        //textContainer.x = stageW / 2;
        //textContainer.y = stageH / 2 + 100;
        //textContainer.alpha = 0;
        //
        //this.textContainer = textContainer;
        //
        ////根据name关键字，异步获取一个json配置文件，name属性请参考resources/resource.json配置文件的内容。
        //RES.getResAsync("description",this.startAnimation,this);


        //游戏场景层，游戏场景相关内容可以放在这里面。
        this.gameLayer = new egret.DisplayObjectContainer();
        this.addChild(this.gameLayer);
        var bitmap:egret.Bitmap = new egret.Bitmap();
        bitmap.texture = RES.getRes("bgImage");
        this.gameLayer.addChild(bitmap);

        //GUI的组件必须都在这个容器内部,UIStage会始终自动保持跟舞台一样大小。
        this.guiLayer = new egret.gui.UIStage();
        this.addChild(this.guiLayer);

        var button:egret.gui.Button = new egret.gui.Button();
        button.horizontalCenter = 0;
        button.verticalCenter = 0;
        button.label = "点击弹窗";
        button.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onButtonClick,this);
        //在GUI范围内一律使用addElement等方法替代addChild等方法。
        this.guiLayer.addElement(button);
    }
    /**
     * 根据name关键字创建一个Bitmap对象。name属性请参考resources/resource.json配置文件的内容。
     */
    private createBitmapByName(name:string):egret.Bitmap {
        var result:egret.Bitmap = new egret.Bitmap();
        var texture:egret.Texture = RES.getRes(name);
        result.texture = texture;
        return result;
    }
    /**
     * 描述文件加载成功，开始播放动画
     */
    private startAnimation(result:Array<any>):void{
        var textContainer:egret.Sprite = this.textContainer;
        var count:number = -1;
        var self:any = this;
        var change:Function = function() {
            count++;
            if (count >= result.length) {
                count = 0;
            }
            var lineArr = result[count];

            self.changeDescription(textContainer, lineArr);

            var tw = egret.Tween.get(textContainer);
            tw.to({"alpha":1}, 200);
            tw.wait(2000);
            tw.to({"alpha":0}, 200);
            tw.call(change, this);
        }

        change();
    }
    /**
     * 切换描述内容
     */
    private changeDescription(textContainer:egret.Sprite, lineArr:Array<any>):void {
        textContainer.removeChildren();
        var w:number = 0;
        for (var i:number = 0; i < lineArr.length; i++) {
            var info:any = lineArr[i];
            var colorLabel:egret.TextField = new egret.TextField();
            colorLabel.x = w;
            colorLabel.anchorX = colorLabel.anchorY = 0;
            colorLabel.textColor = parseInt(info["textColor"]);
            colorLabel.text = info["text"];
            colorLabel.size = 40;
            textContainer.addChild(colorLabel);

            w += colorLabel.width;
        }
    }

    private onButtonClick(event:egret.TouchEvent):void{
        egret.gui.Alert.show("这是一个GUI弹窗!","弹窗")
    }
}


