function makeToast(title, body_text) {

  if (!(document.getElementById("clientRequestToasts"))) {
    const newToastContainer = document.createElement("div");
    newToastContainer.classList.add("toast-container", "top-0", "end-0", "p-2", "position-fixed");
    newToastContainer.setAttribute("id", "clientRequestToasts");

    document.body.appendChild(newToastContainer);
  }

  const newToastContainer = document.getElementById("clientRequestToasts");

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

function removeRequestRequest(url, client_id) {
  let httpRequest = new XMLHttpRequest();

  httpRequest.onreadystatechange = () => {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
        const response = JSON.parse(httpRequest.responseText);

        makeToast(`Request Removed`, `The Request "#${response.request_data}" is removed.`);


        document.getElementById(`clientRow${client_id}`).remove();
      } else {
        makeToast(`Request NOT Removed`, `There was an error removing the client.`)
      }
    }
  }

  httpRequest.open("POST", url);
  httpRequest.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded"
  );
  httpRequest.send(`request_Id=${encodeURIComponent(client_id)}`);
}

function removeRequest(client_id) {
  removeRequestRequest('request_action.php', client_id);
}

function editRequest(client_id) {
  window.location.href = `request_edit.php?request_id=${client_id}`;
}
