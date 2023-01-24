import React, { useRef, useState, useEffect } from 'react';
import { styled } from '@linaria/react';
import {
  CALYPSO,
  CALYPSO_LIGHT,
  CALYPSO_MEDIUM,
  OBSIDIAN,
} from '../UIComponents/colors';
import UISpinner from '../UIComponents/UISpinner';

const Container = styled.div`
  color: ${OBSIDIAN};
  font-family: Avenir Next, Helvetica, Arial, sans-serif;
  font-size: 14px;
  position: relative;
`;

const ControlContainer = styled.div`
  align-items: center;
  background-color: hsl(0, 0%, 100%);
  border-color: hsl(0, 0%, 80%);
  border-radius: 4px;
  border-style: solid;
  border-width: ${props => (props.focused ? '0' : '1px')};
  cursor: default;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  min-height: 38px;
  outline: 0 !important;
  position: relative;
  transition: all 100ms;
  box-sizing: border-box;
  box-shadow: ${props =>
    props.focused ? `0 0 0 2px ${CALYPSO_MEDIUM}` : 'none'};
  &:hover {
    border-color: hsl(0, 0%, 70%);
  }
`;

const ValueContainer = styled.div`
  align-items: center;
  display: flex;
  flex: 1;
  flex-wrap: wrap;
  padding: 2px 8px;
  position: relative;
  overflow: hidden;
  box-sizing: border-box;
`;

const Placeholder = styled.div`
  color: hsl(0, 0%, 50%);
  margin-left: 2px;
  margin-right: 2px;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  box-sizing: border-box;
  font-size: 16px;
`;

const SingleValue = styled.div`
  color: hsl(0, 0%, 20%);
  margin-left: 2px;
  margin-right: 2px;
  max-width: calc(100% - 8px);
  overflow: hidden;
  position: absolute;
  text-overflow: ellipsis;
  white-space: nowrap;
  top: 50%;
  transform: translateY(-50%);
  box-sizing: border-box;
`;

const IndicatorContainer = styled.div`
  align-items: center;
  align-self: stretch;
  display: flex;
  flex-shrink: 0;
  box-sizing: border-box;
`;

const DropdownIndicator = styled.div`
  border-top: 8px solid ${CALYPSO};
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  width: 0px;
  height: 0px;
  margin: 10px;
`;

const InputContainer = styled.div`
  margin: 2px;
  padding-bottom: 2px;
  padding-top: 2px;
  visibility: visible;
  color: hsl(0, 0%, 20%);
  box-sizing: border-box;
`;

const Input = styled.input`
  box-sizing: content-box;
  background: rgba(0, 0, 0, 0) none repeat scroll 0px center;
  border: 0px none;
  font-size: inherit;
  opacity: 1;
  outline: currentcolor none 0px;
  padding: 0px;
  color: inherit;
  font-family: inherit;
  width: ${props => props.size};Í
`;

const InputShadow = styled.div`
  position: absolute;
  opacity: 0;
  font-size: inherit;
`;

const MenuContainer = styled.div`
  position: absolute;
  top: 100%;
  background-color: #fff;
  border-radius: 4px;
  margin-bottom: 8px;
  margin-top: 8px;
  z-index: 9999;
  box-shadow: 0 0 0 1px hsla(0, 0%, 0%, 0.1), 0 4px 11px hsla(0, 0%, 0%, 0.1);
  width: 100%;
`;

const MenuList = styled.div`
  max-height: 300px;
  overflow-y: auto;
  padding-bottom: 4px;
  padding-top: 4px;
  position: relative;
`;

const MenuGroup = styled.div`
  padding-bottom: 8px;
  padding-top: 8px;
`;

const MenuGroupHeader = styled.div`
  color: #999;
  cursor: default;
  font-size: 75%;
  font-weight: 500;
  margin-bottom: 0.25em;
  text-transform: uppercase;
  padding-left: 12px;
  padding-left: 12px;
`;

const MenuItem = styled.div`
  display: block;
  background-color: ${props =>
    props.selected ? CALYPSO_MEDIUM : 'transparent'};
  color: ${props => (props.selected ? '#fff' : 'inherit')};
  cursor: default;
  font-size: inherit;
  width: 100%;
  padding: 8px 12px;
  &:hover {
    background-color: ${props =>
      props.selected ? CALYPSO_MEDIUM : CALYPSO_LIGHT};
  }
`;

export default function AsyncSelect({
  placeholder,
  value,
  loadOptions,
  onChange,
  defaultOptions,
}) {
  const inputEl = useRef();
  const inputShadowEl = useRef();
  const [isFocused, setFocus] = useState(false);
  const [isLoading, setLoading] = useState(false);
  const [localValue, setLocalValue] = useState('');
  const [options, setOptions] = useState(defaultOptions);

  const inputSize = `${
    inputShadowEl.current ? inputShadowEl.current.clientWidth + 10 : 2
  }px`;

  useEffect(() => {
    if (loadOptions) {
      setLoading(true);
      loadOptions(localValue, result => {
        setOptions(result);
        setLoading(false);
      });
    }
  }, [localValue, loadOptions]);

  const renderItems = (items = [], parentKey) => {
    return items.map((item, index) => {
      if (item.options) {
        return (
          <MenuGroup key={`async-select-item-${index}`}>
            <MenuGroupHeader id={`${index}-heading`}>
              {item.label}
            </MenuGroupHeader>
            <div>{renderItems(item.options, index)}</div>
          </MenuGroup>
        );
      } else {
        const key = `async-select-item-${
          parentKey !== undefined ? `${parentKey}-${index}` : index
        }`;
        return (
          <MenuItem
            key={key}
            id={key}
            selected={value && item.value === value.value}
            onClick={() => onChange(item)}
          >
            {item.label}
          </MenuItem>
        );
      }
    });
  };

  return (
    <Container>
      <ControlContainer
        id="leadin-async-selector"
        focused={isFocused}
        onClick={() => {
          if (isFocused) {
            inputEl.current.blur();
          } else {
            inputEl.current.focus();
          }
        }}
      >
        <ValueContainer>
          {localValue === '' &&
            (!value ? (
              <Placeholder>{placeholder}</Placeholder>
            ) : (
              <SingleValue>{value.label}</SingleValue>
            ))}
          <InputContainer>
            <Input
              ref={inputEl}
              onFocus={() => {
                setFocus(true);
              }}
              onBlur={() => {
                setTimeout(() => {
                  setFocus(false);
                  setLocalValue('');
                }, 100);
              }}
              onChange={e => {
                setLocalValue(e.target.value);
              }}
              value={localValue}
              size={inputSize}
              id="asycn-select-input"
            />
            <InputShadow ref={inputShadowEl}>{localValue}</InputShadow>
          </InputContainer>
        </ValueContainer>
        <IndicatorContainer>
          {isLoading && <UISpinner />}
          <DropdownIndicator />
        </IndicatorContainer>
      </ControlContainer>
      {isFocused && (
        <MenuContainer>
          <MenuList>{renderItems(options)}</MenuList>
        </MenuContainer>
      )}
    </Container>
  );
}
