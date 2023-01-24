import React from 'react';
import { styled } from '@linaria/react';
import { MARIGOLD_LIGHT, MARIGOLD_MEDIUM, OBSIDIAN } from './colors';

const AlertContainer = styled.div`
  background-color: ${MARIGOLD_LIGHT};
  border-color: ${MARIGOLD_MEDIUM};
  color: ${OBSIDIAN};
  font-size: 14px;
  align-items: center;
  justify-content: space-between;
  display: flex;
  border-style: solid;
  border-top-style: solid;
  border-right-style: solid;
  border-bottom-style: solid;
  border-left-style: solid;
  border-width: 1px;
  min-height: 60px;
  padding: 8px 20px;
  position: relative;
  text-align: left;
`;

const Title = styled.p`
  font-family: 'Avenir Next';
  font-style: normal;
  font-weight: 700;
  font-size: 16px;
  line-height: 19px;
  color: ${OBSIDIAN};
  margin: 0;
  padding: 0;
`;

const Message = styled.p`
  font-family: 'Avenir Next';
  font-style: normal;
  font-weight: 400;
  font-size: 14px;
  margin: 0;
  padding: 0;
`;

const MessageContainer = styled.div`
  display: flex;
  flex-direction: column;
`;

export default function UIAlert({ titleText, titleMessage, children }) {
  return (
    <AlertContainer>
      <MessageContainer>
        <Title>{titleText}</Title>
        <Message>{titleMessage}</Message>
      </MessageContainer>
      {children}
    </AlertContainer>
  );
}
