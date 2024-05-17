document.addEventListener("DOMContentLoaded", function () {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  const totalPointsElement = document.getElementById("totalPoints");
  const submitButton = document.getElementById("submitButton");
  let totalPoints = 0;

  checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      const points = parseInt(this.getAttribute("data-points"));
      if (this.checked) {
        totalPoints += points;
      } else {
        totalPoints -= points;
      }
      totalPointsElement.textContent = totalPoints;
      submitButton.disabled = totalPoints > 100;
    });
  });
});

// checking player type limits
function checkPlayerTypeLimits() {
  var batsmenCount = 0;
  var allroundersCount = 0;
  var bowlersCount = 0;

  var checkboxes = document.getElementsByName("players[]");
  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].checked) {
      var playerType = checkboxes[i].getAttribute("data-type");
      if (playerType === "batsman") {
        batsmenCount++;
      } else if (playerType === "allrounder") {
        allroundersCount++;
      } else if (playerType === "bowler") {
        bowlersCount++;
      }
    }
  }

  // Update counts display
  document.getElementById("batsmenCount").innerText = batsmenCount;
  document.getElementById("allroundersCount").innerText = allroundersCount;
  document.getElementById("bowlersCount").innerText = bowlersCount;

  // Check if limits are exceeded
  var maxBatsmen = 5;
  var minBatsmen = 3;
  var maxAllrounders = 2;
  var minAllrounders = 1;
  var maxBowlers = 4;
  var minBowlers = 3;

  if (
    batsmenCount > maxBatsmen ||
    batsmenCount < minBatsmen ||
    allroundersCount > maxAllrounders ||
    allroundersCount < minAllrounders ||
    bowlersCount > maxBowlers ||
    bowlersCount < minBowlers
  ) {
    alert("Range: \n 3 - 5 Batsmen \n 1 - 2 All - Rounders \n 3 - 4 Bowlers.");
    return false;
  }

  return true;
}
