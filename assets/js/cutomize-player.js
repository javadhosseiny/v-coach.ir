function addNewButton(data) {
  var myPlayer = data.player,
    controlBar,
    newElement = document.createElement("div"),
    newLink = document.createElement("a");

  newElement.id = data.id ? data.id : "exampleId";
  newElement.className = data.className ? data.className : "exampleClass";
  newLink.innerHTML = data.icon ? data.icon : "icon";
  newElement.appendChild(newLink);

  controlBar = document.getElementsByClassName("vjs-control-bar")[0];
  insertBeforeNode = document.getElementsByClassName(
    "vjs-fullscreen-control"
  )[0];
  controlBar.insertBefore(newElement, insertBeforeNode);

  return newElement;
}
if ($("#my-video")) {
  var player = videojs("my-video", {
    playbackRates: [0.5, 1, 1.5, 2],
  });

  var forward = addNewButton({
    player: player,
    icon: '<i class="fas fa-redo forward-icon"></i>',
    id: "forward-icon-container",
  });
  forward.onclick = function () {
    let current = player.currentTime();
    player.currentTime(current + 15);
  };

  var backward = addNewButton({
    player: player,
    icon: '<i class="fas fa-redo backward-icon"></i>',
    id: "backward-icon-container",
  });
  backward.onclick = function () {
    let current = player.currentTime();
    player.currentTime(current - 15);
  };
}
