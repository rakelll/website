function makeToast(title, body_text) {

	if (!(document.getElementById("Request_TypeToasts"))) {
	  const newToastContainer = document.createElement("div");
	  newToastContainer.classList.add("toast-container", "top-0", "end-0", "p-2", "position-fixed");
	  newToastContainer.setAttribute("id", "Request_TypeToasts");

	  document.body.appendChild(newToastContainer);
	}

	const newToastContainer = document.getElementById("Request_TypeToasts");

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

function removeRequest_TypeRequest(url, Request_Type_id) {
  let httpRequest = new XMLHttpRequest();

  httpRequest.onreadystatechange = () => {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
  	  if (httpRequest.status === 200) {
  	    const response = JSON.parse(httpRequest.responseText);

  	    makeToast(`Request_Type Removed`, `The Request_Type "${response.request_type}" is removed.`);


  	    document.getElementById(`Request_TypeRow${Request_Type_id}`).remove();
  	  } else {
  	    makeToast(`Request_Type NOT Removed`, `There was an error removing the Request_Type "${response.request_type}".`)
  	  }
    }
  }

  httpRequest.open("POST", url);
  httpRequest.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded"
  );
  httpRequest.send(`Request_Type_id=${encodeURIComponent(Request_Type_id)}`);
}

function removeRequest_Type(Request_Type_id) {
removeRequest_TypeRequest('request_type_action.php', Request_Type_id);
}

function changeRequest_TypeRequest(url, Request_Type_id, new_Request_Type) {
let httpRequest = new XMLHttpRequest();

httpRequest.onreadystatechange = () => {
  if (httpRequest.readyState === XMLHttpRequest.DONE) {
	if (httpRequest.status === 200) {
	  const response = JSON.parse(httpRequest.responseText);

	  makeToast(`Request_Type Changed`, `The Request_Type "${response.last_Request_Type}" is changed to "${response.request_type}".`);
	} else {
	  makeToast(`Request_Type NOT Changed`, `There was an error changed the Request_Type "${response.last_Request_Type}" to "${response.request_type}".`)
	}
  }
};

httpRequest.open("POST", url);
httpRequest.setRequestHeader(
  "Content-type",
  "application/x-www-form-urlencoded",
);
httpRequest.send(`Request_Type_id=${encodeURIComponent(Request_Type_id)}&Request_Type_change=${encodeURIComponent(new_Request_Type)}`);
}

function editRequest_Type(Request_Type_id) {
const editButton = document.getElementById(`Request_TypeButton${Request_Type_id}`);
const editSpan = document.getElementById(`Request_TypeSpan${Request_Type_id}`);

editButton.classList.replace("btn-outline-warning", "btn-outline-success");
editButton.classList.replace("fa-pen-to-square", "fa-check");
editButton.setAttribute("onclick", `saveRequest_TypeChange(${Request_Type_id})`);

editSpan.setAttribute("contenteditable", "true");
editSpan.style.border = "1px solid gray";
}

function saveRequest_TypeChange(Request_Type_id) {
  const Request_TypeSpan = document.getElementById(`Request_TypeSpan${Request_Type_id}`).textContent.trim();

  changeRequest_TypeRequest('request_type_action.php', Request_Type_id, Request_TypeSpan);

  const editButton = document.getElementById(`Request_TypeButton${Request_Type_id}`);
  const editSpan = document.getElementById(`Request_TypeSpan${Request_Type_id}`);

  editButton.classList.replace("btn-outline-success", "btn-outline-warning");
  editButton.classList.replace("fa-check", "fa-pen-to-square");
  editButton.setAttribute("onclick", `editRequest_Type(${Request_Type_id})`);

  editSpan.setAttribute("contenteditable", "false");
  editSpan.style.border = "none";
}
