import { styled } from '@linaria/react';

export default styled.div`
  background-image: ${props =>
    `url(${props.pluginPath}/assets/images/hubspot.svg)`};
  background-color: #f5f8fa;
  background-repeat: no-repeat;
  background-position: center 25px;
  background-size: 120px;
  color: #33475b;
  font-family: Avenir Next, Helvetica, Arial, sans-serif;
  font-size: 14px;

  padding: ${props => props.padding || '90px 20% 25px'};

  p {
    font-size: inherit !important;
    line-height: 24px;
    margin: 4px 0;
  }
`;
