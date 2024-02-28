import React from "react";
import '../../../../../styles/Home/HomeCards/components/homePostIt.css';
import { TypeAnimation } from 'react-type-animation';

function HomePostIt({color = 'yellow'})
{
    return(
        <div className="homePostItWrapper">
            <div className={'homePostItHeader ' + color}></div>

            <div className={'homePostItBody ' + color}>
                <TypeAnimation
                    sequence={[
                        'Make it simple.',
                        1000,
                        'Make it fast.',
                        1000,
                        'Make it open-source.',
                        1000,
                        'Make it free.',
                        1000,
                        'Make it transparent.',
                        1000,
                        'Make it secure.',
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
