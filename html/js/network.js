function get(url) {
	var init = arguments.length <= 1 || arguments[1] === undefined ? {} : arguments[1];
	var headers = arguments.length <= 2 || arguments[2] === undefined ? new Headers({}) : arguments[2];


	var myInit = Object.assign({
		method: 'GET',
		mode: 'cors',
		cache: 'default',
		credentials: 'same-origin',
		headers: headers
	}, init);

	return fetch(url, myInit);
}

function post(url) {
	var body = arguments.length <= 1 || arguments[1] === undefined ? {} : arguments[1];
	var init = arguments.length <= 2 || arguments[2] === undefined ? {} : arguments[2];
	var headers = arguments.length <= 3 || arguments[3] === undefined ? new Headers({}) : arguments[3];


	var myInit = Object.assign({
		method: 'POST',
		mode: 'cors',
		cache: 'default',
		credentials: 'same-origin',
		headers: headers,
		body: body
	}, init);

	return fetch(url, myInit);
}

function postJson(url) {
	var body = arguments.length <= 1 || arguments[1] === undefined ? {} : arguments[1];
	var init = arguments.length <= 2 || arguments[2] === undefined ? {} : arguments[2];


	var headers = new Headers({
		'Accept': 'application/json',
		'Content-type': 'application/json'
	});

	var myInit = Object.assign({
		method: 'POST',
		mode: 'cors',
		cache: 'default',
		credentials: 'same-origin',
		headers: headers,
		body: JSON.stringify(body)
	}, init);

	return fetch(url, myInit);
}

function getJson(url) {
	var query = arguments.length <= 1 || arguments[1] === undefined ? {} : arguments[1];
	var init = arguments.length <= 2 || arguments[2] === undefined ? {} : arguments[2];


	var headers = new Headers({
		'Accept': 'application/json',
		'Content-type': 'application/json'
	});

	var myInit = Object.assign({
		method: 'GET',
		mode: 'cors',
		cache: 'default',
		credentials: 'same-origin',
		headers: headers
	}, init);

	if (!/\?/.test(url)) {
		url += '?';
	}

	url += encodeQueryData(query);

	return fetch(url, myInit).then(function (response) {
		return response.json();
	});
}

function postJsonThenJson(url) {
	var body = arguments.length <= 1 || arguments[1] === undefined ? {} : arguments[1];
	var init = arguments.length <= 2 || arguments[2] === undefined ? {} : arguments[2];


	return postJson(url, body, init).then(function (response) {
		var contentType = response.headers.get("content-type");
		if (contentType && contentType.indexOf("application/json") !== -1) {
			return response.json();
		}

		console.log("client requested json but no json returned; status is " + response.status + ' (' + response.statusText + ')');

		return {success: false, message: "uknown error"};
	});
}

function encodeQueryData(data) {
	var ret = [];
	for (var d in data) {
		if (data.hasOwnProperty(d)) {
			ret.push(encodeURIComponent(d) + "=" + encodeURIComponent(data[d]));
		}
	}

	return ret.join("&");
}
