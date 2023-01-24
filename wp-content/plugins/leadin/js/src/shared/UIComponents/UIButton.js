import { styled } from '@linaria/react';
import { HEFFALUMP, LORAX, OLAF } from './colors';

export default styled.button`
  background-color:${props => (props.use === 'tertiary' ? HEFFALUMP : LORAX)};
  border: 3px solid ${props => (props.use === 'tertiary' ? HEFFALUMP : LORAX)};
  color: ${OLAF}
  border-radius: 3px;
  font-size: 14px;
  line-height: 14px;
  padding: 12px 24px;
  font-family: Avenir Next W02, Helvetica, Arial, sans-serif;
  font-weight: 500;
  white-space: nowrap;
`;
