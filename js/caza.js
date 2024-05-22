function makeToast(title, body_text) {

  if (!(document.getElementById("clientCazaToasts"))) {
    const newToastContainer = document.createElement("div");
    newToastContainer.classList.add("toast-container", "top-0", "end-0", "p-2", "position-fixed");
    newToastContainer.setAttribute("id", "clientCazaToasts");

    document.body.appendChild(newToastContainer);
  }

  const newToastContainer = document.getElementById("clientCazaToasts");

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

function removeCazaRequest(url, caza_id) {
  let httpRequest = new XMLHttpRequest();

  httpRequest.onreadystatechange = () => {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
        const response = JSON.parse(httpRequest.responseText);

        makeToast(`Caza Removed`, `The caza "${response.caza_name}" is removed.`);


        document.getElementById(`cazaRow${caza_id}`).remove();
      } else {
        makeToast(`Caza NOT Removed`, `There was an error removing the caza.`)
      }
    }
  }

  httpRequest.open("POST", url);
  httpRequest.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded"
  );
  httpRequest.send(`caza_id=${encodeURIComponent(caza_id)}`);
}

function removeCaza(caza_id) {
  removeCazaRequest('caza_action.php', caza_id);
}

function changeCazaRequest(url, caza_id, new_caza) {
  let httpRequest = new XMLHttpRequest();

  httpRequest.onreadystatechange = () => {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
        const response = JSON.parse(httpRequest.responseText);

        makeToast(`Caza Changed`, `The caza "${response.last_caza}" is changed to "${response.caza_name}".`);
      } else {
        makeToast(`Caza NOT Changed`, `There was an error changing the caza.`);
      }
    }
  };

  httpRequest.open("POST", url);
  httpRequest.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded",
  );
  httpRequest.send(`caza_id=${encodeURIComponent(caza_id)}&caza_name=${encodeURIComponent(new_caza)}`);
}

function editCaza(caza_id) {
  const editButton = document.getElementById(`CazaButton${caza_id}`);
  const editSpan = document.getElementById(`CazaSpan${caza_id}`);

  editButton.classList.replace("btn-outline-warning", "btn-outline-success");
  editButton.classList.replace("fa-pen-to-square", "fa-check");
  editButton.setAttribute("onclick", `saveCazaChange(${caza_id})`);

  editSpan.setAttribute("contenteditable", "true");
  editSpan.style.border = "1px solid gray";
}

function saveCazaChange(caza_id) {
  const cazaSpan = document.getElementById(`CazaSpan${caza_id}`).textContent.trim();

  changeCazaRequest('caza_action.php', caza_id, cazaSpan);

  const editButton = document.getElementById(`CazaButton${caza_id}`);
  const editSpan = document.getElementById(`CazaSpan${caza_id}`);

  editButton.classList.replace("btn-outline-success", "btn-outline-warning");
  editButton.classList.replace("fa-check", "fa-pen-to-square");
  editButton.setAttribute("onclick", `editCaza(${caza_id})`);

  editSpan.setAttribute("contenteditable", "false");
  editSpan.style.border = "none";
}
