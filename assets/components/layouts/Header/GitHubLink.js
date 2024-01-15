import React from 'react';
import GitHubLogo from "../../../img/header/github-logo.svg";
import '../../../styles/Header/gitHubLink.css';

const GitHubLink = () => {
    return(
        <a target="_blank" href="https://github.com/BaptisteLT/Wallstit" id="gitHubWrapper">
            <img id="gitHubLogo" src={GitHubLogo} />
            <div>Project Code</div>
        </a>
    )
}
export default GitHubLink;
