import React from 'react';
import GitHubLogo from "../../../img/header/github-logo.svg";
import '../../../styles/Header/gitHubLink.css';
import '../../../styles/Header/providerLinkGeneral.css';

const GitHubLink = () => {
    return(
        <a target="_blank" href="https://github.com/BaptisteLT/Wallstit" className="provider-wrapper gitHubLogo_wrapper">
            <img class="gitHubLogo" src={GitHubLogo} />
            <div>Project Code</div>
        </a>
    )
}
export default GitHubLink;
