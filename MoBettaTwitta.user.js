// ==UserScript==
// @name         MoBettaTwitta
// @namespace    http://your.homepage/
// @version      0.1
// @description  Allows you to break that 140 character limit in a way that will still work for people without this extension
// @author       Christopher Wirt
// @match        https://twitter.com/*
// @grant        none
// ==/UserScript==
window.foundTweets = [];
window.foundButtons = [];
window.formsAdded = [];
window.foundTweetBoxes = [];

console.log = console.__proto__.log;
(function() {
    console.log = console.__proto__.log
    var addButton = function() {
    console.log = console.__proto__.log;
        var forms = document.getElementsByClassName("tweet-form");
        
        for (var fi = 0; fi < forms.length; fi++) {
            var form = forms[fi];
            if (form === undefined)
                continue;
            var button = form.getElementsByClassName("js-tweet-btn")[0];
            if(button === undefined || window.foundButtons.indexOf(button) >= 0)
                continue;
            var myBtn = document.createElement("button");
            myBtn.className = "tweet-btn";
            myBtn.innerHTML = "MoBetta Tweet!";
            myBtn.style.marginLeft = "15px";
            
            window.foundButtons.push(button);
            var getCallback = function(tweetElement, button) {
                return function(data, textstatus, jqXHR) {
                    tweetElement.innerHTML = "https://mobettatwitta.azurewebsites.net/?id=" + data.id;
                    button.disabled = "";
                }
            }
            myBtn.addEventListener('click', function(e) {
                var tweetbox = document.getElementsByClassName("tweet-content")[0].getElementsByClassName("tweet-box")[0].getElementsByTagName("div")[0];
                text = tweetbox.innerHTML;
                console.log(tweetbox);
                $.getJSON("https://mobettatwitta.azurewebsites.net/?create&tweet=" + text).done(getCallback(tweetbox, button));
                e.preventDefault();
            });
            $(button).after(myBtn);
        }
    }

    var findAndReplace = function() {
        console.log = console.__proto__.log
        var tweets = document.getElementsByClassName("tweet-text");
        for (var i = 0; i < tweets.length; i++) {
            var tweet = tweets[i];
            if (window.foundTweets.indexOf(tweet) > -1) {
                continue;
            }
            if (tweet !== undefined) {
                var links = tweet.getElementsByTagName("a");
                for (var j = 0; j < links.length; j++) {
                    var link = links[j];
                    if (link !== undefined) {
                        var url = link.getAttribute("data-expanded-url");
                        if (url == null || url === undefined)
                            continue;
                        if (url.indexOf("https://mobettatwitta.azurewebsites.net/?id=") > -1) {


                            url += "&raw";
                            var getCallback = function(tweetElement) {
                                return function(data, textstatus, jqXHR) {
                                    tweetElement.innerHTML = data.tweet;
                                }
                            }
                            tweet.innerHTML = "Loading MoBetta Tweet...";
                            $.getJSON(url).done(getCallback(tweet));
                            window.foundTweets.push(tweet);
                        }
                    }
                }
            }
        }
    }

    setInterval(findAndReplace, 15);
    setInterval(addButton, 15);
})();