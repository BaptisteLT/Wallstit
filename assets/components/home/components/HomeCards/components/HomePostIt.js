import React from "react";
import '../../../../../styles/Home/HomeCards/components/homePostIt.css';
import { TypeAnimation } from 'react-type-animation';


function HomePostIt({color = 'yellow'})
{
    return(
        <div className="homePostWrapper">
            <div className={'homePostHeader ' + color}></div>

            <div className={'homePostBody ' + color}>
                <TypeAnimation
                    sequence={[
                        'Make it simple.',
                        1000,
                        'Make it quick.',
                        1000,
                        'Make it free.',
                        1000,
                    ]}
                    wrapper="span"
                    speed={50}
                    style={{ fontSize: '24px', margin: '10px' }}
                    repeat={Infinity}
                />
            </div>
        </div>
    )
}

export default HomePostIt;
