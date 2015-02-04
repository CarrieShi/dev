class GUIControlTest extends egret.DisplayObjectContainer {

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
        egret.gui.Theme.load("resource/theme/theme.thm");
        //设置加载进度界面
        this.loadingView  = new LoadingUI();
        this.stage.addChild(this.loadingView);

        //初始化Resource资源加载库
        RES.addEventListener(RES.ResourceEvent.CONFIG_COMPLETE,this.onConfigComplete,this);
        RES.loadConfig("resource/resource.json","resource/");
        //初始化themeResource资源加载库***************GUI
        RES.loadConfig("resource/resource_simple.json","resource/");
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
        //游戏场景层，游戏场景相关内容可以放在这里面。
        //this.gameLayer = new egret.DisplayObjectContainer();
        //this.addChild(this.gameLayer);
        //var bitmap:egret.Bitmap = new egret.Bitmap();
        //bitmap.texture = RES.getRes("bgImage");
        //this.gameLayer.addChild(bitmap);

        //GUI的组件必须都在这个容器内部,UIStage会始终自动保持跟舞台一样大小。
        this.guiLayer = new egret.gui.UIStage();
        this.addChild(this.guiLayer);
        //this.text();
        this.button();
        //this.toggleButton();
        //this.initToggleBar();
        //this.checkBox();
        //this.initRadioButton();
        //this.initRadioButtonWithGroup();
        //this.horizontalSlider();
        //this.verticalSlider();
    }

    private text() {
        var label:egret.gui.Label = new egret.gui.Label();
        label.text = "很多的文字很多的文字很多的文字很多的文字很多的文字很多的文字";
        label.fontFamily = "Tahoma";//设置字体
        label.textColor = 0xFFFFFF;//设置颜色
        label.size = 20;//设置文本字号
        label.bold = true;//设置是否加粗
        label.italic = true;//设置是否斜体
        label.textAlign = "left";//设置水平对齐方式
        label.verticalAlign = "top";//设置垂直对齐方式
        label.lineSpacing = 2;//行间距
        //文字超出固定宽度，自动换行失败
        label.width = 100;
        label.height = 30;
        label.maxDisplayedLines = 2;//最大行数
        label.padding = 10;//文字和边框之间的距离 paddingLeft paddingRight paddingTop paddingBottom

        //在GUI范围内一律使用addElement等方法替代addChild等方法。
        this.guiLayer.addElement(label);
    }

    private button() {
        var button:egret.gui.Button = new egret.gui.Button();
        button.horizontalCenter = 0;
        button.verticalCenter = 0;
        button.label = "点击弹窗";
        //Egret GUI的组件和皮肤是分离的，所以打算为组件实现不同的外观的时候，实现的方式就是自定义一个皮肤。
        button.width = 200;
        button.height = 100;
        this.guiLayer.addElement(button);
        button.addEventListener(egret.TouchEvent.TOUCH_TAP,this.onButtonClick,this);
    }

    private onButtonClick(event:egret.TouchEvent):void{
        egret.gui.Alert.show("这是一个GUI弹窗!","弹窗",this.closeHandler);
        console.log("button touched");
    }

    private closeHandler(evt:egret.gui.CloseEvent):void {
        console.log("用户关闭了GUI弹窗");
    }

    private toggleButton() {
        var btn:egret.gui.ToggleButton = new egret.gui.ToggleButton();
        btn.x = btn.y = 20;
        btn.label = "我是ToggleButton";
        btn.addEventListener(egret.Event.CHANGE,this.changeHandler,this);
        this.guiLayer.addElement(btn);
    }

    private checkBox() {
        var cbx:egret.gui.CheckBox = new egret.gui.CheckBox();
        cbx.x = cbx.y = 20;
        cbx.id = "1";
        cbx.label = "选择我1";
        cbx.addEventListener(egret.Event.CHANGE,this.changeHandler,this);
        this.guiLayer.addElement(cbx);

        var cbx2:egret.gui.CheckBox = new egret.gui.CheckBox();
        cbx2.id = "2";
        cbx2.x = 20;
        cbx2.y = 50;
        cbx2.label = "选择我2";
        cbx2.addEventListener(egret.Event.CHANGE,this.changeHandler,this);
        this.guiLayer.addElement(cbx2);
    }

    private changeHandler(evt:egret.Event):void {
        console.log(evt.target.id);
        console.log(evt.target.selected);
    }

    //4个ToggleButton
    private toggleBtns:egret.gui.ToggleButton[];

    private initToggleBar():void {
        this.toggleBtns = [];
        for(var i:number=0;i<4;i++) {
            var btn:egret.gui.ToggleButton = new egret.gui.ToggleButton();
            btn.label = i+1+"";
            btn.y = 100;
            btn.width = 80;
            btn.x = 20+i*80;
            btn.addEventListener(egret.Event.CHANGE,this.toggleChangeHandler,this);
            this.toggleBtns.push(btn);
            this.guiLayer.addElement(btn);
        }
    }

    private toggleChangeHandler(evt:egret.Event):void {
        for(var i:number=0;i<this.toggleBtns.length;i++) {
            var btn:egret.gui.ToggleButton = this.toggleBtns[i];
            btn.selected = (btn == evt.target);
            if(btn.selected) {
                console.log(evt.target);
                console.log("btn"+(i+1) + " is selected");
            }

        }
    }

    private initRadioButton():void {
        //为每个单选按钮设置groupName属性，对应的类型是字符串。
        //当若干个单选按钮的groupName是同一个字符串，则系统自动将它们归结到一组
        var rdb:egret.gui.RadioButton = new egret.gui.RadioButton();
        rdb.label = "选择我1";
        rdb.value = 1;
        rdb.y = 20;
        rdb.groupName = "G1";
        rdb.addEventListener(egret.Event.CHANGE,this.radioChangeHandler,this);
        this.guiLayer.addElement(rdb);

        var rdb2:egret.gui.RadioButton = new egret.gui.RadioButton();
        rdb2.label = "选择我2";
        rdb2.value = 2;
        rdb2.y = 50;
        rdb2.selected = true;//默认选项
        rdb2.groupName = "G1";
        rdb2.addEventListener(egret.Event.CHANGE,this.radioChangeHandler,this);
        this.guiLayer.addElement(rdb2);
    }

    private radioChangeHandler(evt:egret.Event):void {
        console.log(evt.target.value);
    }

    private initRadioButtonWithGroup():void {
        //单选按钮需要绑定到一个组上
        var radioGroup:egret.gui.RadioButtonGroup = new egret.gui.RadioButtonGroup();
        radioGroup.addEventListener(egret.Event.CHANGE,this.groupChangeHandler,this);
        //创建单选按钮
        var rdb:egret.gui.RadioButton = new egret.gui.RadioButton();
        rdb.label = "选择我1";
        rdb.value = 1;
        rdb.y = 20;
        rdb.group = radioGroup;
        this.guiLayer.addElement(rdb);
        var rdb2:egret.gui.RadioButton = new egret.gui.RadioButton();
        rdb2.label = "选择我2";
        rdb2.value = 2;
        rdb2.y = 50;
        rdb2.selected = true;//默认选项
        rdb2.group = radioGroup;
        this.guiLayer.addElement(rdb2);
    }

    private groupChangeHandler(evt:egret.Event):void {
        var radioGroup:egret.gui.RadioButtonGroup = evt.target;
        console.log(radioGroup.selectedValue);
    }

    private horizontalSlider() {
        var hSlider:egret.gui.HSlider = new egret.gui.HSlider();
        hSlider.width = 200;
        hSlider.x = 20;
        hSlider.y = 20;
        hSlider.minimum = 0;//定义最小值
        hSlider.maximum = 100;//定义最大值
        hSlider.value = 10;//定义默认值
        hSlider.addEventListener(egret.Event.CHANGE,this.changeSliderHandler,this);
        this.guiLayer.addElement(hSlider);
    }
    private changeSliderHandler(evt:egret.TouchEvent):void {
        console.log(evt.target.value);
    }

    private verticalSlider() {
        var vSlider:egret.gui.VSlider = new egret.gui.VSlider();
        vSlider.height = 200;
        vSlider.x = 100;
        vSlider.y = 60;
        vSlider.minimum = 100;//定义最小值
        vSlider.maximum = 200;//定义最大值
        vSlider.value = 120;//定义默认值
        vSlider.addEventListener(egret.Event.CHANGE,this.changeSliderHandler,this);
        this.guiLayer.addElement(vSlider);
    }

}


