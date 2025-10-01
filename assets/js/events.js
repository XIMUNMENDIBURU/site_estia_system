document.addEventListener("DOMContentLoaded", () => {
  initEventVisibility()
})

function initEventVisibility() {
  // Adjust date visibility for all events
  var events = document.querySelectorAll(".listeEvent")
  events.forEach((event, index) => {
    var dateId = "dateId" + index
    var titreId = "titreId" + index

    function adjustVisibility() {
      var dateElement = document.getElementById(dateId)
      var titleElement = document.getElementById(titreId)

      if (dateElement && titleElement) {
        var dateRect = dateElement.getBoundingClientRect()
        var titleRect = titleElement.getBoundingClientRect()

        if (dateRect.right > titleRect.left) {
          dateElement.style.visibility = "hidden"
        } else {
          dateElement.style.visibility = "visible"
        }
      }
    }

    window.addEventListener("resize", adjustVisibility)
    window.addEventListener("load", adjustVisibility)
    adjustVisibility() // Call immediately
  })
}

function editElement(elementId) {
  window.location.href = "../edit.php?id=" + elementId
}

function showConfirmationPopup(idElement) {
  document.getElementById("confirmationPopup" + idElement).style.display = "block"
  window.elementIdToDelete = idElement
}

function hideConfirmationPopup() {
  if (window.elementIdToDelete) {
    document.getElementById("confirmationPopup" + window.elementIdToDelete).style.display = "none"
  }
}

function confirmSuppression() {
  if (window.elementIdToDelete) {
    window.location.href = "../delete.php?id=" + window.elementIdToDelete
  }
}
