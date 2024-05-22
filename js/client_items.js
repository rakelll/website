function makeToast(title, body_text) {

  if (!(document.getElementById("clientClientToasts"))) {
    const newToastContainer = document.createElement("div");
    newToastContainer.classList.add("toast-container", "top-0", "end-0", "p-2", "position-fixed");
    newToastContainer.setAttribute("id", "clientClientToasts");

    document.body.appendChild(newToastContainer);
  }

  const newToastContainer = document.getElementById("clientClientToasts");

  const toastId = (Math.random() + 1).toString(36).substring(2);


  const newToast = document.createElement("div");
  newToast.classList.add("toast");
  newToast.setAttribute("role", "alert");
  newToast.setAttribute("aria-live", "assertive");
  newToast.setAttribute("aria-atomic", "true");
  newToast.setAttribute("id", toastId);

  const newToastHeader = document.createElement("div");
  newToastHeader.classList.add("toast-header", "justify-content-between");

  const newToastHeaderTitle = document.createElement("strong");
  newToastHeaderTitle.textContent = title;

  const newToastHeaderCloseButton = document.createElement("button")
  newToastHeaderCloseButton.type = "button";
  newToastHeaderCloseButton.classList.add("btn-close");
  newToastHeaderCloseButton.setAttribute("data-bs-dismiss", "toast");
  newToastHeaderCloseButton.setAttribute("aria-label", "Close");

  newToastHeader.appendChild(newToastHeaderTitle);
  newToastHeader.appendChild(newToastHeaderCloseButton);

  const newToastBody = document.createElement("div");
  newToastBody.classList.add("toast-body");
  newToastBody.textContent = body_text;

  newToast.appendChild(newToastHeader);
  newToast.appendChild(newToastBody);

  newToastContainer.appendChild(newToast);

  const myToast = new bootstrap.Toast(`#${toastId}`);
  myToast.show();
}

function removeClientRequest(url, client_id) {
  let httpRequest = new XMLHttpRequest();

  httpRequest.onreadystatechange = () => {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
        const response = JSON.parse(httpRequest.responseText);

        makeToast(`Item Removed`, `The item "${response.client_name}" is removed.`);


        document.getElementById(`clientRow${client_id}`).remove();
      } else {
        makeToast(`Item NOT Removed`, `There was an error removing the item.`)
      }
    }
  }

  httpRequest.open("POST", url);
  httpRequest.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded"
  );
  httpRequest.send(`client_item_id=${encodeURIComponent(client_id)}`);
}

function removeClient(client_id) {
  removeClientRequest('client_items_action.php', client_id);
}
