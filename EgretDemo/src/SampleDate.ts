class SampleDate extends egret.DisplayObjectContainer {
    public constructor() {
        super();

        //创建一个男朋友
        var boy:Boy = new Boy();
        boy.name = "男朋友";
        //创建一个女朋友
        var girl:Girl = new Girl();
        girl.name = "女朋友";
        if(boy.hasEventListener(DateEvent.DATE)) {
            console.log("侦听器注册了");
        } else {
            console.log("侦听器未注册");
            //注册侦听器
            //public addEventListener(type:string, listener:Function, thisObject:any, useCapture:boolean = false, priority:number = 0)
            //type表示事件类型
            //listener就是用来处理事件的侦听器。
            //thisObject比较特殊，一般我们填写this。因为TypeScript与JavaScript的this作用域不同，其this指向也会不同。
            //如果不填写this的话，那么编译后的代码会发生错误。
            //priority该属性为一个number类型，当数字越大，则优先级越大。
            boy.addEventListener(DateEvent.DATE,girl.getDate,girl);
            //男朋友发送要求
            boy.order();
            //约会邀请完成后，移除侦听器
            boy.removeEventListener(DateEvent.DATE,girl.getDate,girl);
        }
    }
}

class Boy extends egret.Sprite {
    public constructor() {
        super();
    }

    public order() {
        //生成约会事件对象
        var daterEvent:DateEvent = new DateEvent(DateEvent.DATE);
        //添加对应的约会信息
        daterEvent._year = 2014;
        daterEvent._month = 8;
        daterEvent._date = 2;
        daterEvent._where = "肯德基";
        daterEvent._todo = "共进晚餐";
        //发送要求事件
        this.dispatchEvent(daterEvent);
    }
}

class Girl extends egret.Sprite {
    public constructor() {
        super();
    }

    //创建侦听器 listenerName(evt:Event):void {...}
    public getDate(evt:DateEvent) {
        console.log("得到了" + evt.target.name + "的邀请！" );
        console.log("会在" + evt._year + "年" + evt._month + "月" + evt._date + "日，在"+ evt._where+ evt._todo);
    }
}

class DateEvent extends egret.Event {
    public static DATE:string = "约会";
    public _year:number = 0;
    public _month:number = 0;
    public _date:number = 0;
    public _where:string = "";
    public _todo:string = "";

    //type:string 事件的类型。类型区分大小写。
    //bubbles:boolean 表示事件是否为冒泡事件。如果事件可以冒泡，则此值为 true；否则为 false。
    //cancelable:boolean表示是否可以阻止与事件相关联的行为。如果可以取消该行为，则此值为 true；否则为 false。
    public constructor(type:string, bubbles:boolean=false, cancelable:boolean=false) {
        super(type,bubbles,cancelable);
    }
}