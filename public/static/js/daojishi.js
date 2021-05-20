// first select all the elements that we need from the page
const dayDOM = document.querySelector(".days");
const hourDOM = document.querySelector(".hours");
const minuteDOM = document.querySelector(".minutes");
const secondDOM = document.querySelector(".seconds");
let timer;
  //const targetDate = "2022/04/09";

  //   取这个值并将其传递到函数中 

  //每秒钟为一个新的now创建一个计时器。 
 	const targDate = (targetDate) =>{
  timer = setInterval(() => {
    if (targetDate) {
      startCountdown(targetDate);
    } else {
      controlError();
    }
  }, 1000);
	}
const startCountdown = (date) => {
  // 我们使用getTime（）函数以毫秒为单位获取时间

  const actualDate = new Date(date);
  const dateInMilliseconds = actualDate.getTime();

  const now = new Date();
  const nowInMilliseconds = now.getTime();

  const remainingTime = dateInMilliseconds - nowInMilliseconds;

  // 把剩下的时间分成几天、几小时、几分钟和几秒钟

  const days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
  const hours = Math.floor(
    (remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
  );

  const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));

  const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);


    updateDom({ seconds, minutes, hours, days });
  
};

const updateDom = ({ seconds, minutes, hours, days }) => {
  // 用正确的值更新dom
  dayDOM.innerHTML = `${days} 天`;
  secondDOM.innerHTML = `&nbsp;&nbsp;${seconds} 秒`;
  minuteDOM.innerHTML = `&nbsp;&nbsp;${minutes} 分`;
  hourDOM.innerHTML = `&nbsp;&nbsp;${hours} 时`;
};
