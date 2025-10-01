document.addEventListener("DOMContentLoaded", () => {
  // Self-destruct button functionality
  initSelfDestructButton()

  // Sponsor scrolling
  initSponsorScrolling()
})

function initSelfDestructButton() {
  var theCount
  var alarm = document.getElementById("alarm")
  var panel = document.getElementById("panel")
  var turnOff = document.getElementById("turn-off")
  var turnOffHor = document.getElementById("closing")
  var detonate = document.getElementById("detonate")
  var time = document.getElementById("time")
  var cover = document.getElementById("cover")
  var btn = document.getElementById("activate")
  var abort = document.getElementById("abort")
  var reload = document.getElementById("restart")

  if (!alarm) return // Exit if elements don't exist

  alarm.volume = 0.5

  function showCountDown() {
    time.innerText = time.innerText - 1
    if (time.innerText == 0) {
      clearInterval(theCount)
      time.classList.add("crono")
      abort.classList.add("hide")
      detonate.classList.add("show")
      setTimeout(() => {
        turnOff.classList.add("close")
        turnOffHor.classList.add("close")
        reload.classList.add("show")
        alarm.pause()
      }, 1500)
    }
  }

  cover.addEventListener("click", function () {
    if (this.className == "box") this.classList.add("opened")
    else this.classList.remove("opened")
  })

  btn.addEventListener("click", function () {
    this.classList.add("pushed")
    alarm.load()
    alarm.currentTime = 10.1
    alarm.play()
    setTimeout(() => {
      panel.classList.add("show")
      time.innerText = 3
      theCount = setInterval(showCountDown, 1000)
      alarm.load()
      alarm.play()
    }, 500)
  })

  abort.addEventListener("click", () => {
    btn.classList.remove("pushed")
    panel.classList.remove("show")
    clearInterval(theCount)
    time.innerText = 3
    alarm.pause()
    alarm.currentTime = 10
    alarm.play()
  })

  reload.addEventListener("click", function () {
    panel.classList.remove("show")
    turnOff.classList.remove("close")
    turnOffHor.classList.remove("close")
    abort.classList.remove("hide")
    detonate.classList.remove("show")
    cover.classList.remove("opened")
    btn.classList.remove("pushed")
    this.classList.remove("show")
    time.classList.remove("crono")
    time.innerText = 3
  })

  setTimeout(() => {
    cover.classList.remove("opened")
  }, 100)
}

function initSponsorScrolling() {
  var container = document.getElementById("defiler")
  if (!container) return

  var step = 1
  var speed = 30
  var valeurScroll = 0

  function scroll() {
    container.scrollLeft += step
    if (container.scrollLeft == valeurScroll) {
      container.scrollLeft = 0
    }
    if (container.scrollLeft % 205 == 0) {
      clearInterval(scroller)
      setTimeout(() => {
        scroller = setInterval(scroll, speed)
      }, 20)
    }
    valeurScroll = container.scrollLeft
  }

  var scroller = setInterval(scroll, speed)
}
