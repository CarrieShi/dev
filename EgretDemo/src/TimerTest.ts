class TimerTest extends egret.DisplayObjectContainer {

    public constructor() {
        super();

        //创建一个计时器对象器
        //egret.Timer(delay, repeatCount) 两个属性分别表示每次间隔的时间（以毫秒为单位）和执行的次数（如果次数为0，则表示不停的执行）。
        var timer:egret.Timer = new egret.Timer(500, 5);
        //注册事件侦听器
        //TimerEvent.TIMER 计时过程中触发
        timer.addEventListener(egret.TimerEvent.TIMER, this.timerFunc, this);
        //TimerEvent.TIMER_COMPLETE 计时结束后触发
        timer.addEventListener(egret.TimerEvent.TIMER_COMPLETE, this.timerComFunc, this);
        //开始计时
        timer.start();
    }

    private timerFunc() {
        console.log("计时");
    }

    private timerComFunc() {
        console.log("计时结束");
    }
}